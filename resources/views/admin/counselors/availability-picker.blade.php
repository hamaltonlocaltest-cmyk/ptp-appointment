{{--
    Reusable weekly availability picker.
    Expects (optional): $existingAvailability = [['day'=>'Monday','start_time'=>'09:00','end_time'=>'17:00'], ...]
    Renders hidden inputs named availability[N][day], availability[N][start_time], availability[N][end_time]
    — identical contract to the old version, so the controller needs no changes.
--}}
@php
    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    $dayShort = ['Monday'=>'Mon','Tuesday'=>'Tue','Wednesday'=>'Wed','Thursday'=>'Thu','Friday'=>'Fri','Saturday'=>'Sat','Sunday'=>'Sun'];
    $gridStartMinutes = 6 * 60;   // 6:00 AM
    $gridEndMinutes   = 22 * 60;  // 10:00 PM
    $slotMinutes      = 30;       // half-hour rows
    $rowCount = ($gridEndMinutes - $gridStartMinutes) / $slotMinutes;
@endphp

<style>
.avail-toolbar { display:flex; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:14px; }
.avail-preset-btn {
    border:1px solid #d7dbe6; background:#fff; color:#444; font-size:12px; font-weight:600;
    padding:6px 12px; border-radius:20px; cursor:pointer; transition:.15s;
}
.avail-preset-btn:hover { background:#eff9f8; border-color:#087a7f; color:#087a7f; }
.avail-copy-btn {
    border:1px solid #1b5e20; background:#fff; color:#1b5e20; font-size:12px; font-weight:600;
    padding:6px 12px; border-radius:20px; cursor:pointer; margin-left:auto;
}
.avail-copy-btn:hover { background:#1b5e20; color:#fff; }
.avail-grid-wrap { overflow-x:auto; border:1px solid #e0e4ec; border-radius:8px; }
.avail-grid {
    display:grid;
    grid-template-columns: 56px repeat(7, minmax(86px, 1fr));
    user-select:none;
    min-width:700px;
}
.avail-grid-head {
    font-size:11px; font-weight:700; color:#555; text-align:center; padding:8px 4px;
    background:#f8f9fc; border-bottom:2px solid #e0e4ec; position:sticky; top:0;
}
.avail-grid-head.time-col { background:#fff; border-right:1px solid #e0e4ec; }
.avail-time-label {
    font-size:10px; color:#9e9e9e; text-align:right; padding:0 8px 0 0;
    border-right:1px solid #e0e4ec; display:flex; align-items:center; justify-content:flex-end;
    height:18px;
}
.avail-cell {
    height:18px; border-bottom:1px solid #f0f1f5; border-right:1px solid #f0f1f5;
    cursor:pointer; transition:background .08s;
}
.avail-cell:hover { background:#dbfff9; }
.avail-cell.on-hour { border-bottom:1px solid #e0e4ec; }
.avail-cell.selected { background:#c9a6f0; }
.avail-cell.committed { background:#087a7f; }
.avail-day-col:last-child .avail-cell { border-right:none; }

.avail-summary { margin-top:16px; }
.avail-day-row { display:flex; align-items:flex-start; gap:10px; padding:6px 0; border-bottom:1px dashed #eee; }
.avail-day-row:last-child { border-bottom:none; }
.avail-day-name { width:90px; flex-shrink:0; font-size:13px; font-weight:600; color:#333; padding-top:4px; }
.avail-pills { display:flex; flex-wrap:wrap; gap:6px; flex:1; }
.avail-pill {
    display:inline-flex; align-items:center; gap:6px; background:#eff9f8; color:#087a7f;
    font-size:12px; font-weight:600; padding:4px 6px 4px 10px; border-radius:20px;
}
.avail-pill button {
    background:none; border:none; color:#087a7f; cursor:pointer; font-size:13px; line-height:1; padding:2px;
}
.avail-pill button:hover { color:#dc3545; }
.avail-empty-day { font-size:12px; color:#bbb; padding-top:5px; font-style:italic; }
</style>

<div id="availabilityPicker" data-grid-start="{{ $gridStartMinutes }}" data-slot-minutes="{{ $slotMinutes }}">

  
    <div class="avail-toolbar">
        <span style="font-size:12px; color:#777; font-weight:600; margin-right:4px;">Quick add:</span>
        <button type="button" class="avail-preset-btn" data-preset="09:00-17:00">9 AM – 5 PM</button>
        <button type="button" class="avail-preset-btn" data-preset="06:00-12:00">Morning</button>
        <button type="button" class="avail-preset-btn" data-preset="12:00-17:00">Afternoon</button>
        <button type="button" class="avail-preset-btn" data-preset="17:00-21:00">Evening</button>
        <button type="button" class="avail-copy-btn" id="copyToAllBtn">
            <i class="fas fa-copy mr-1"></i> Copy first day to all
        </button>
    </div>


    <div class="avail-grid-wrap">
        <div class="avail-grid" id="availGrid">
            <div class="avail-grid-head time-col"></div>
            @foreach($days as $day)
            <div class="avail-grid-head">{{ $dayShort[$day] }}</div>
            @endforeach

            @for($row = 0; $row < $rowCount; $row++)
                @php
                    $minutes = $gridStartMinutes + ($row * $slotMinutes);
                    $isHour  = $minutes % 60 === 0;
                    $label   = $isHour ? \Carbon\Carbon::createFromTime(0,0)->addMinutes($minutes)->format('g A') : '';
                @endphp
                <div class="avail-time-label">{{ $label }}</div>
                @foreach($days as $day)
                <div class="avail-cell {{ $isHour ? 'on-hour' : '' }}"
                     data-day="{{ $day }}" data-row="{{ $row }}"></div>
                @endforeach
            @endfor
        </div>
    </div>

    
    <div class="avail-summary" id="availSummary">
        @foreach($days as $day)
        <div class="avail-day-row" data-day-row="{{ $day }}">
            <div class="avail-day-name">{{ $day }}</div>
            <div class="avail-pills" data-day-pills="{{ $day }}">
                <span class="avail-empty-day">No hours set</span>
            </div>
        </div>
        @endforeach
    </div>

   
    <div id="availabilityHiddenInputs"></div>

    @error('availability')<div class="text-danger mt-2" style="font-size:13px;">{{ $message }}</div>@enderror
</div>

<script>
(function () {
    const GRID_START   = {{ $gridStartMinutes }};
    const SLOT_MINUTES = {{ $slotMinutes }};
    const DAYS = @json($days);

    // slots = { Monday: [{start:'09:00', end:'17:00'}, ...], Tuesday: [...], ... }
    const slots = {};
    DAYS.forEach(d => slots[d] = []);

    // Seed from existing data (edit page) or old() input (validation failure redisplay)
    const seedData = @json($existingAvailability ?? []);
    seedData.forEach(row => {
        if (row.day && row.start_time && row.end_time && DAYS.includes(row.day)) {
            slots[row.day].push({ start: row.start_time, end: row.end_time });
        }
    });

    function minutesToTime(mins) {
        const h = Math.floor(mins / 60).toString().padStart(2, '0');
        const m = (mins % 60).toString().padStart(2, '0');
        return `${h}:${m}`;
    }
    function timeToMinutes(t) {
        const [h, m] = t.split(':').map(Number);
        return h * 60 + m;
    }
    function fmt12(t) {
        const [h, m] = t.split(':').map(Number);
        const period = h >= 12 ? 'PM' : 'AM';
        const h12 = h % 12 === 0 ? 12 : h % 12;
        return `${h12}:${m.toString().padStart(2,'0')} ${period}`;
    }

    function mergeOverlaps(daySlots) {
        if (daySlots.length <= 1) return daySlots;
        const sorted = [...daySlots].sort((a, b) => timeToMinutes(a.start) - timeToMinutes(b.start));
        const merged = [sorted[0]];
        for (let i = 1; i < sorted.length; i++) {
            const last = merged[merged.length - 1];
            const cur = sorted[i];
            if (timeToMinutes(cur.start) <= timeToMinutes(last.end)) {
                if (timeToMinutes(cur.end) > timeToMinutes(last.end)) last.end = cur.end;
            } else {
                merged.push(cur);
            }
        }
        return merged;
    }

    function addSlot(day, start, end) {
        if (start >= end) return;
        slots[day].push({ start, end });
        slots[day] = mergeOverlaps(slots[day]);
        render();
    }

    function removeSlot(day, index) {
        slots[day].splice(index, 1);
        render();
    }

    function render() {
        // Hidden inputs
        const container = document.getElementById('availabilityHiddenInputs');
        container.innerHTML = '';
        let idx = 0;
        DAYS.forEach(day => {
            slots[day].forEach(s => {
                container.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="availability[${idx}][day]" value="${day}">
                    <input type="hidden" name="availability[${idx}][start_time]" value="${s.start}">
                    <input type="hidden" name="availability[${idx}][end_time]" value="${s.end}">
                `);
                idx++;
            });
        });

        // Summary pills
        DAYS.forEach(day => {
            const pillsWrap = document.querySelector(`[data-day-pills="${day}"]`);
            pillsWrap.innerHTML = '';
            if (slots[day].length === 0) {
                pillsWrap.innerHTML = '<span class="avail-empty-day">No hours set</span>';
                return;
            }
            slots[day].forEach((s, i) => {
                const pill = document.createElement('span');
                pill.className = 'avail-pill';
                pill.innerHTML = `${fmt12(s.start)} – ${fmt12(s.end)} <button type="button" data-remove-day="${day}" data-remove-idx="${i}">&times;</button>`;
                pillsWrap.appendChild(pill);
            });
        });

        // Grid committed cells
        document.querySelectorAll('.avail-cell').forEach(cell => cell.classList.remove('committed'));
        DAYS.forEach(day => {
            slots[day].forEach(s => {
                const startRow = (timeToMinutes(s.start) - GRID_START) / SLOT_MINUTES;
                const endRow   = (timeToMinutes(s.end) - GRID_START) / SLOT_MINUTES;
                for (let r = startRow; r < endRow; r++) {
                    const cell = document.querySelector(`.avail-cell[data-day="${day}"][data-row="${r}"]`);
                    if (cell) cell.classList.add('committed');
                }
            });
        });
    }

    // Remove-pill clicks (event delegation)
    document.getElementById('availSummary').addEventListener('click', function (e) {
        const btn = e.target.closest('[data-remove-day]');
        if (btn) {
            removeSlot(btn.dataset.removeDay, parseInt(btn.dataset.removeIdx, 10));
        }
    });

    // Preset buttons add the same time range to whichever day you click next isn't intuitive,
    // so presets apply to ALL days that currently have zero slots (fast first-pass setup).
    document.querySelectorAll('.avail-preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const [start, end] = this.dataset.preset.split('-');
            DAYS.slice(0, 5).forEach(day => { // Mon-Fri by default for presets
                addSlot(day, start, end);
            });
        });
    });

    // Copy first non-empty day's slots to all other days
    document.getElementById('copyToAllBtn').addEventListener('click', function () {
        const sourceDay = DAYS.find(d => slots[d].length > 0);
        if (!sourceDay) {
            alert('Add at least one time slot first, then use Copy to apply it to every day.');
            return;
        }
        DAYS.forEach(day => {
            if (day !== sourceDay) {
                slots[day] = JSON.parse(JSON.stringify(slots[sourceDay]));
            }
        });
        render();
    });

   
    let dragging = false;
    let dragDay = null;
    let dragStartRow = null;

    function clearSelectionStyles() {
        document.querySelectorAll('.avail-cell.selected').forEach(c => c.classList.remove('selected'));
    }

    document.querySelectorAll('.avail-cell').forEach(cell => {
        cell.addEventListener('mousedown', function (e) {
            e.preventDefault();
            dragging = true;
            dragDay = this.dataset.day;
            dragStartRow = parseInt(this.dataset.row, 10);
            this.classList.add('selected');
        });

        cell.addEventListener('mouseenter', function () {
            if (!dragging || this.dataset.day !== dragDay) return;
            const curRow = parseInt(this.dataset.row, 10);
            const lo = Math.min(dragStartRow, curRow);
            const hi = Math.max(dragStartRow, curRow);
            clearSelectionStyles();
            for (let r = lo; r <= hi; r++) {
                const c = document.querySelector(`.avail-cell[data-day="${dragDay}"][data-row="${r}"]`);
                if (c) c.classList.add('selected');
            }
        });
    });

    document.addEventListener('mouseup', function () {
        if (!dragging) return;
        dragging = false;

        const selected = document.querySelectorAll('.avail-cell.selected');
        if (selected.length === 0) return;

        const rows = Array.from(selected).map(c => parseInt(c.dataset.row, 10));
        const lo = Math.min(...rows);
        const hi = Math.max(...rows);

        const start = minutesToTime(GRID_START + lo * SLOT_MINUTES);
        const end   = minutesToTime(GRID_START + (hi + 1) * SLOT_MINUTES);

        addSlot(dragDay, start, end);
        clearSelectionStyles();
        dragDay = null;
        dragStartRow = null;
    });

    // Touch support (basic tap-to-add-one-slot, since drag-touch is unreliable across devices)
    document.querySelectorAll('.avail-cell').forEach(cell => {
        cell.addEventListener('touchend', function (e) {
            e.preventDefault();
            const day = this.dataset.day;
            const row = parseInt(this.dataset.row, 10);
            const start = minutesToTime(GRID_START + row * SLOT_MINUTES);
            const end   = minutesToTime(GRID_START + (row + 1) * SLOT_MINUTES);
            addSlot(day, start, end);
        });
    });

    // Expose a validation hook the parent form can call before submit
    window.__availabilityHasAnySlot = function () {
        return DAYS.some(d => slots[d].length > 0);
    };

    render();
})();
</script>