<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2P Appointment — Counseling Management System</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
	
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --navy:   #1a237e;
            --green:  #2e8a44;
            --purple: #dc2426;
            --light:  #f4f6f9;
            --white:  #ffffff;
            --text:   #000;
            --muted:  #333;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        /* .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e8eaf0;
            padding: 0 5%;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        } */
		.navbar {padding: 0 5%; height: 80px;}
        .nav-brand {
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-brand .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--navy), #3949ab);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px;
        }
        .nav-brand span { color: #90caf9; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-links a {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .nav-link-ghost { color: var(--muted); }
        .nav-link-ghost:hover { background: var(--light); color: var(--navy); }
        .nav-btn-counselor {
            background: var(--green);
            color: #fff !important;
        }
        .nav-btn-counselor:hover { background: #2e7d32; transform: translateY(-1px); }
        .nav-btn-counselee {
            background: var(--purple);
            color: #fff !important;
        }
        .nav-btn-counselee:hover { background: #6a1b9a; transform: translateY(-1px); }
        .nav-btn-admin {
            background: var(--light);
            color: var(--navy) !important;
            border: 1px solid #c5cae9;
        }
        .nav-btn-admin:hover { background: #e8eaf6; }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d1642 0%, #1a237e 40%, #283593 70%, #1565c0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 100px 5% 60px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(ellipse at center, rgba(255,255,255,0.03) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }
        .hero-circles {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .hero-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            animation: float 6s ease-in-out infinite;
        }
        .hero-circle:nth-child(1) { width:300px; height:300px; top:-100px; right:-50px; animation-delay:0s; }
        .hero-circle:nth-child(2) { width:200px; height:200px; bottom:50px; left:-50px; animation-delay:2s; }
        .hero-circle:nth-child(3) { width:150px; height:150px; top:30%; right:10%; animation-delay:4s; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }

        .hero-content { position: relative; z-index: 1; max-width: 800px; }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: #90caf9;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .hero h1 {
            font-size: clamp(32px, 5vw, 58px);
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero h1 span { color: #90caf9; }
        .hero p {
            font-size: 17px;
            color: rgba(255,255,255,0.75);
            line-height: 1.8;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: start;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }
        .hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 32px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .hero-btn-counselor {
            background: linear-gradient(135deg, #1b5e20 0%, #2e8a44 100%);
            color: #fff;
            box-shadow: 0 8px 30px rgba(27,94,32,0.4);
        }
        .hero-btn-counselor:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(27,94,32,0.5);
            color: #fff;
            text-decoration: none;
        }
        .hero-btn-counselee {
            background: linear-gradient(135deg, #8b1113 0%, #dc2426 100%);
            color: #fff;
            box-shadow: 0 8px 30px rgba(74,20,140,0.4);
        }
        .hero-btn-counselee:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(74,20,140,0.5);
            color: #fff;
            text-decoration: none;
        }
        .hero-btn-ghost {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .hero-btn-ghost:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
            color: #fff;
            text-decoration: none;
        }
        .hero-stats {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .hero-stat { text-align: center; }
        .hero-stat .num { font-size: 28px; font-weight: 800; color: #fff; }
        .hero-stat .lbl { font-size: 12px; color: rgba(255,255,255,0.55); text-transform: uppercase; letter-spacing: 0.5px; }
        .hero-stat-divider { width: 1px; background: rgba(255,255,255,0.15); }

        /* ===== PORTAL CARDS ===== */
        .portals {
            padding: 80px 5%;
            background: var(--light);
        }
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-badge {
            display: inline-block;
            background: #e7f2db;
            color: #2e8a44;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .section-header h2 {
            font-size: clamp(24px, 3vw, 36px);
            font-weight: 800;
            color: var(--text);
            margin-bottom: 12px;
        }
        .section-header p { font-size: 15px; color: var(--muted); max-width: 500px; margin: 0 auto; }

        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
            max-width: 900px;
            margin: 0 auto;
        }
        .portal-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            transition: all 0.3s;
            border: 1px solid #f0f2f5;
        }
        .portal-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(0,0,0,0.12);
        }
        .portal-card-header {
            padding: 36px 32px 28px;
            position: relative;
            overflow: hidden;
        }
        .portal-card-header::after {
            content: '';
            position: absolute;
            bottom: -30px; right: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        .portal-card-header.counselor { background: linear-gradient( 135deg, #ffffff 0%, #f9fefb 30%, #f1fbf4 70%, #e7f7ec 100% ) }
        .portal-card-header.counselee { background: linear-gradient( 135deg, #fffdfd 0%, #fff5f5 35%, #ffeaea 70%, #ffdede 100% ) }
		.portal-card-header.counselor  .portal-icon{background: #2e8a44;}
		.portal-card-header.counselee  .portal-icon{background: #dc2426;}
        .portal-icon {
            width: 60px; height: 60px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #fff;
            margin-bottom: 20px;
        }
        .portal-card-header h3 { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .portal-card-header p { font-size: 15px; color: #333; font-weight: 500;  line-height: 1.6; }
		.portal-card-header.counselor h3 {color: #2e8a44;}
		.portal-card-header.counselee h3 {color: #dc2426;}
		
        .portal-card-body { padding: 28px 32px; }
        .portal-features { list-style: none; margin-bottom: 28px; padding:0}
        .portal-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #333;
            padding: 7px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .portal-features li:last-child { border: none; }
        .portal-features li .check {
            width: 22px; height: 22px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; flex-shrink: 0;
        }
		.portal-features li .check i {font-size: 18px;}
        .check-green { background: #2e8a44; color: #fff; }
        .check-purple { background: #dc2426; color: #fff; }
        .portal-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            margin-bottom: 10px;
        }
        .portal-login-btn:hover { transform: translateY(-2px); text-decoration: none; }
        .btn-counselor-solid { background: linear-gradient(135deg, #1b5e20 0%, #2e8a44 100%); color:#fff; box-shadow:0 4px 16px rgba(27,94,32,0.3); }
        .btn-counselor-solid:hover { box-shadow:0 8px 24px rgba(27,94,32,0.4); color:#fff; }
        .btn-counselee-solid { background: linear-gradient(135deg, #8b1113 0%, #dc2426 100%); color:#fff; box-shadow:0 4px 16px rgba(74,20,140,0.3); }
        .btn-counselee-solid:hover { box-shadow:0 8px 24px rgba(74,20,140,0.4); color:#fff; }
        .portal-register-link {
            display: block;
            text-align: center;
            font-size: 13px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #e0e4ec;
            text-decoration: none;
            color: var(--muted);
            transition: all 0.2s;
        }
        .portal-register-link:hover { border-color: currentColor; text-decoration: none; }
		.portal-register-link.reg-counselor{color: #2e8a44;  font-weight: 700;}
		.portal-register-link.reg-counselee{color: #dc2426;  font-weight: 700;}
        .reg-counselor:hover { color: #1b5e20; border-color: #1b5e20; background: #f1f8e9; }
        .reg-counselee:hover { color: #dc2426; border-color: #dc2426; background: #ffeeee; }

        /* ===== HOW IT WORKS ===== */
        .how-it-works { padding: 80px 5%; background: #fff; }
		
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin: 0 auto;
        }
        .step-card {
            text-align: center;
            padding: 32px 24px;
            border-radius: 16px;
            border: 1px solid #e8eaf0;
            transition: all 0.3s;
            position: relative;
        }
        .step-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
        .step-num {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 800;
            margin: 0 auto 16px;
            color: #fff;
        }
        .step-card h4 { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .step-card p { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* ===== COUNSEL TYPES ===== */
        .counsel-types { padding: 80px 5%; background: var(--light); }
        .types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
            max-width: 1000px;
            margin: 0 auto;
        }
		/* Mobile: 2 columns */
			@media (max-width: 576px) {
				.types-grid {
					grid-template-columns: repeat(2, 1fr);
					gap: 12px;
				}
			}
        .type-card {
            background: #fff;
            border-radius: 14px;
            padding: 24px 16px;
            text-align: center;
            border: 1px solid #f0f2f5;
            transition: all 0.3s;
            cursor: default;
        }
        .type-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .type-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            margin: 0 auto 14px;
        }
        .type-card h4 { font-size: 13px; font-weight: 700; color: var(--text); }

        /* ===== FEATURES ===== */
        .features { padding: 80px 5%; background: #fff; }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .feature-card {
            padding: 28px 24px;
            border-radius: 16px;
            border: 1px solid #e8eaf0;
            background: #fff;
            transition: all 0.3s;
        }
        .feature-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.08); transform: translateY(-3px); }
        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }
        .feature-card h4 { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .feature-card p { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* ===== CTA ===== */
        .cta {
            padding: 80px 5%;
            background: linear-gradient(135deg, #f3fcf5 0%, #d9f3df 60%, #bdeac8 100%);
            text-align: center;
        }
        .cta h2 { font-size: clamp(24px,3vw,38px); font-weight: 800; color: #2e8a44; margin-bottom: 14px; }
        .cta p { font-size: 16px; color: #333; margin-bottom: 36px; max-width: 500px; margin-left: auto; margin-right: auto; }
        .cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

        /* ===== FOOTER ===== */
        footer {
            background: #257037;
            padding: 40px 5% 24px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 24px;
        }
        .footer-brand { font-size: 18px; font-weight: 800; color: #fff; }
        .footer-brand span { color: #e8f8eb; }
        .footer-links { display: flex; gap: 20px; flex-wrap: wrap; }
        .footer-links a { font-size: 13px; color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #e8f8eb; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.07); padding-top: 20px; text-align: center; font-size: 12px; color: rgba(255,255,255,0.9); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .nav-links .nav-link-ghost { display: none; }
            .hero-stat-divider { display: none; }
            .portal-grid { grid-template-columns: 1fr; }
        }
		
		
		
    </style>
</head>
<body>




<style>
	.navbar-brand img{
    max-height:60px;
}

.navbar-nav .nav-link{
    font-weight:500;
    color:#444;
    padding:.75rem 1rem;
}

.navbar-nav .nav-link:hover{
    color:#198754;
}

.nav-btn-counselor,
.nav-btn-counselee,
.nav-btn-admin{
    border-radius:6px;
    padding:.55rem 1rem;
    font-weight:600;
    white-space:nowrap;
}

@media (max-width:991.98px){

    .navbar-nav{
        padding-top:20px;
        align-items:stretch !important;
    }

    .navbar-nav .nav-item{
        width:100%;
    }

    .navbar-nav .btn{
        width:100%;
        text-align:center;
    }

    .navbar-nav .nav-link{
        text-align:center;
    }

}
</style>

<nav class="navbar navbar-expand-lg bg-white py-3 shadow-sm">
    <div class="container-fluid">

        <!-- Logo -->
        <a class="navbar-brand nav-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/persontoperson-logo.png') }}"
                 class="img-fluid"
                 alt="Person to Person Logo"
                 style="max-height:65px;">
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse justify-content-end"
             id="mainNavbar">

            <ul class="navbar-nav align-items-lg-center ms-auto">

                <li class="nav-item">
                    <a href="#portals"
                       class="nav-link nav-link-ghost">
                        Portals
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#how-it-works"
                       class="nav-link nav-link-ghost">
                        How It Works
                    </a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="{{ route('counselor.login') }}"
                       class="btn nav-btn-counselor">
                        <i class="bi bi-person-workspace me-1"></i>
                        Counselor
                    </a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="{{ route('counselee.login') }}"
                       class="btn nav-btn-counselee">
                        <i class="bi bi-person-heart me-1"></i>
                        Counselee
                    </a>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="{{ route('admin.login') }}"
                       class="btn nav-btn-admin">
                        <i class="bi bi-key me-1"></i>
                        Admin
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>



<style>
	.hero-section {
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    padding: 100px 0 70px;
    display: flex;
    align-items: center;

    background: linear-gradient(
        135deg,
        #ffffff 0%,
        #f8fff8 20%,
        #eefaf0 45%,
        #e3f7e6 70%,
        #d8f2dc 100%
    );
}

.hero-section::before{
    content:"";
    position:absolute;
    top:-50%;
    left:-50%;
    width:200%;
    height:200%;
    background:
    radial-gradient(circle,
    rgba(76,175,80,.10) 0%,
    rgba(76,175,80,.05) 35%,
    transparent 70%);
    animation:pulse 12s ease-in-out infinite;
    pointer-events:none;
}

.hero-circles{
    position:absolute;
    inset:0;
    overflow:hidden;
    pointer-events:none;
}

.hero-circle{
    position:absolute;
    border-radius:50%;
    background:rgba(46,204,113,.10);
    animation:float 12s ease-in-out infinite;
}

.hero-circle:nth-child(1){
    width:320px;
    height:320px;
    top:-120px;
    right:-80px;
}

.hero-circle:nth-child(2){
    width:220px;
    height:220px;
    bottom:-60px;
    left:-60px;
    animation-delay:2s;
}

.hero-circle:nth-child(3){
    width:160px;
    height:160px;
    top:28%;
    right:12%;
    animation-delay:4s;
}

.hero-circle:nth-child(4){
    width:90px;
    height:90px;
    left:8%;
    top:18%;
    animation-delay:1s;
}

.hero-circle:nth-child(5){
    width:60px;
    height:60px;
    right:22%;
    bottom:18%;
    animation-delay:3s;
}

@keyframes float{

    0%,100%{
        transform:translateY(0) scale(1);
    }

    50%{
        transform:translateY(-25px) scale(1.08);
    }

}

@keyframes pulse{

    0%,100%{
        transform:scale(1);
        opacity:1;
    }

    50%{
        transform:scale(1.08);
        opacity:.8;
    }

}

.hero-title{
    font-size:64px;
    font-weight:800;
    line-height:1.1;
    color:#1f2937;
}

.hero-title span{
    color:#1ca34a;
}

.hero-desc{
    font-size:18px;
    color:#666;
    line-height:1.8;
    max-width:540px;
}

.hero-buttons .btn{
    border-radius: 8px;
  font-weight: 600;
  padding: 9px 13px !important;
  font-size: 16px;
}

.stat-card{
    background:#fff;
    border:1px solid #ececec;
    border-radius:18px;
    text-align:center;
    padding:28px 15px;
    height:100%;
    transition:.3s;
    box-shadow:0 5px 20px rgba(0,0,0,.05);
}

.stat-card:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 35px rgba(0,0,0,.08);
}

.stat-icon{
    width:58px;
    height:58px;
    border-radius:50%;
    background:#eaf8ef;
    color:#16a34a;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:auto auto 15px;
    font-size:28px;
}

.stat-card h3{
    font-size:24px;
    font-weight:700;
    color:#198754;
    margin-bottom:5px;
}

.stat-card p{
    font-size:15px;
    color:#666;
    margin:0;
}

@media(max-width:991px){

.hero-title{
    font-size:48px;
}

.hero-section{
    text-align:center;
}

.hero-desc{
    margin:auto;
}

.hero-buttons{
    justify-content:center;
}

}

.hero-image img{

    mix-blend-mode:multiply;

}

@media(max-width:576px){

.hero-title{
    font-size:38px;
}

.hero-buttons .btn{
    width:100%;
}

.stat-card{
    padding:20px 10px;
}

.stat-card h3{
    font-size:28px;
}

}
</style>
<section class="hero-section py-5">
	<div class="hero-circles">
        <div class="hero-circle"></div>
        <div class="hero-circle"></div>
        <div class="hero-circle"></div>
    </div>
    <div class="container">
        <div class="row align-items-center">

            <!-- Left Content -->
            <div class="col-lg-6">

                <h1 class="hero-title mb-4">
                    Your Journey to
                    <span class="text-success d-block">Better Wellbeing</span>
                    Starts Here
                </h1>

                <p class="hero-desc mb-4">
                    Connect with professional counselors for personalized support.
                    Book appointments easily, get guidance on life's challenges,
                    and take the first step towards a healthier you.
                </p>

             
                <div class="hero-buttons d-flex flex-wrap gap-2 mb-5">

                    <a href="#" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-person-workspace me-2"></i>
                        I'm a Counselor
                    </a>

                    <a href="#" class="btn btn-danger btn-lg px-4">
                        <i class="bi bi-heart-fill me-2"></i>
                        I Need Counseling
                    </a>

                    <a href="#" class="btn btn-outline-success btn-lg px-4">
                        <i class="bi bi-play-circle me-2"></i>
                        How It Works
                    </a>

                </div>

             
                <div class="row g-3">

                    <div class="col-6 col-md-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-person-check"></i>
                            </div>

                            <h3>500+</h3>

                            <p>Sessions Done</p>

                        </div>

                    </div>

                    <div class="col-6 col-md-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-emoji-smile"></i>
                            </div>

                            <h3>50+</h3>

                            <p>Expert Counselors</p>

                        </div>

                    </div>

                    <div class="col-6 col-md-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-people"></i>
                            </div>

                            <h3>10+</h3>

                            <p>Specializations</p>

                        </div>

                    </div>

                    <div class="col-6 col-md-3">

                        <div class="stat-card">

                            <div class="stat-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <h3>98%</h3>

                            <p>Satisfaction</p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Right Image -->
            <div class="col-lg-6">
				<div class="hero-image">
					<!-- Place your hero image here -->
					<img src="{{ asset('images/counselee-img.jpg') }}" class="img-fluid" alt="Person to Person Logo">
				</div>
            </div>

        </div>
    </div>
</section>


<section class="hero d-none">
    <div class="hero-circles">
        <div class="hero-circle"></div>
        <div class="hero-circle"></div>
        <div class="hero-circle"></div>
    </div>
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-shield-alt"></i> Trusted Counseling Platform
        </div>
        <h1>Your Journey to <span>Better Wellbeing</span> Starts Here</h1>
        <p>Connect with professional counselors for personalized support. Book appointments easily, get guidance on life's challenges, and take the first step toward a healthier you.</p>
        <div class="hero-buttons">
            <a href="{{ route('counselor.login') }}" class="hero-btn hero-btn-counselor">
                <i class="fas fa-user-md"></i>
                I'm a Counselor
            </a>
            <a href="{{ route('counselee.login') }}" class="hero-btn hero-btn-counselee">
                <i class="fas fa-heart"></i>
                I Need Counseling
            </a>
            <a href="#how-it-works" class="hero-btn hero-btn-ghost">
                <i class="fas fa-play-circle"></i>
                How It Works
            </a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="num">500+</div>
                <div class="lbl">Sessions Done</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="num">50+</div>
                <div class="lbl">Counselors</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="num">10+</div>
                <div class="lbl">Specializations</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="num">98%</div>
                <div class="lbl">Satisfaction</div>
            </div>
        </div>
    </div>
</section>


<section class="portals" id="portals">
    <div class="section-header">
        <div class="section-badge">Choose Your Portal</div>
        <h2>Two Portals, One Platform</h2>
        <p>Whether you're a counseling professional or seeking support, we have the right portal for you.</p>
    </div>
    <div class="portal-grid">

     
        <div class="portal-card">
            <div class="portal-card-header counselor">
                <div class="portal-icon"><i class="bi bi-person-fill"></i></div>
                <h3>Counselor Portal</h3>
                <p>Manage your appointments, track your clients, and grow your counseling practice with our professional tools.</p>
            </div>
            <div class="portal-card-body">
                <ul class="portal-features">
                    <li>
                        <span class="check check-green"><i class="bi bi-check"></i></span>
                        Manage appointment schedule
                    </li>
                    <li>
                        <span class="check check-green"><i class="bi bi-check"></i></span>
                        View and manage counselee profiles
                    </li>
                    <li>
                        <span class="check check-green"><i class="bi bi-check"></i></span>
                        Track session history and notes
                    </li>
                    <li>
                        <span class="check check-green"><i class="bi bi-check"></i></span>
                        Receive appointment notifications
                    </li>
                    <li>
                        <span class="check check-green"><i class="bi bi-check"></i></span>
                        Update your profile and specialization
                    </li>
                </ul>
                <a href="{{ route('counselor.login') }}" class="portal-login-btn btn-counselor-solid">
                    <i class="bi bi-box-arrow-in-right"></i> Login as Counselor
                </a>
                <a href="{{ route('counselor.register') }}" class="portal-register-link reg-counselor">
                     <i class="bi bi-person-plus me-2"></i> New counselor? Register here
                </a>
            </div>
        </div>

       
        <div class="portal-card">
            <div class="portal-card-header counselee">
                <div class="portal-icon"><i class="bi bi-heart-fill"></i></div>
                <h3>Counselee Portal</h3>
                <p>Find the right counselor, book appointments, and get the professional support you deserve on your wellness journey.</p>
            </div>
            <div class="portal-card-body">
                <ul class="portal-features">
                    <li>
                        <span class="check check-purple"><i class="bi bi-check"></i></span>
                        Browse available counselors
                    </li>
                    <li>
                        <span class="check check-purple"><i class="bi bi-check"></i></span>
                        Book and manage appointments
                    </li>
                    <li>
                        <span class="check check-purple"><i class="bi bi-check"></i></span>
                        Choose your counseling type
                    </li>
                    <li>
                        <span class="check check-purple"><i class="bi bi-check"></i></span>
                        View appointment history
                    </li>
                    <li>
                        <span class="check check-purple"><i class="bi bi-check"></i></span>
                        Secure and confidential sessions
                    </li>
                </ul>
                <a href="{{ route('counselee.login') }}" class="portal-login-btn btn-counselee-solid">
                    <i class="bi bi-person-workspace"></i> Login as Counselee
                </a>
                <a href="{{ route('counselee.register') }}" class="portal-register-link reg-counselee">
                    <i class="bi bi-person-plus me-2"></i> New here? Create an account
                </a>
            </div>
        </div>

    </div>
</section>



<style>
	.step-icon{
    position:relative;
    width:80px;
    height:80px;
    margin:0 auto 25px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:34px;
}

.step-icon.icon-blue, .step-icon.icon-purple{background: #e7f2db;}
.step-icon.icon-blue i, .step-icon.icon-purple i{color: #2e8a44;}
.step-icon.icon-blue .step-number, .step-icon.icon-purple .step-number {background: #2e8a44; color: #fff;}

.step-icon.icon-green, .step-icon.icon-orange{background: #fcebeb;}
.step-icon.icon-green i, .step-icon.icon-orange i {color: #dc2426;}
.step-icon.icon-green .step-number, .step-icon.icon-orange .step-number {background: #dc2426; color: #fff;}

.step-number{
    position:absolute;
    top:-6px;
    right:-6px;
    width:28px;
    height:28px;
    border-radius:50%;
    background:#fff;
    color:#333;
    font-size:14px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 4px 10px rgba(0,0,0,.2);
}

@media only screen and (max-width: 767px) {
			.how-it-works{padding: 80px 2%}
			.steps-grid{grid-template-columns: repeat(2, 1fr);}
			.step-card{padding: 20px 16px 0;}
			.step-icon{width: 60px; height: 60px; font-size: 24px;}
			.step-number{top: 0; right: 0; width: 20px; height: 20px; font-size: 12px;}
			.features-grid{grid-template-columns: repeat(2, 1fr); gap: 16px;}
		}

</style>



    


<section class="how-it-works" id="how-it-works">
	
	<div class="container">
		<div class="row">
			<div class="col-12">
	
				<div class="section-header">
					<div class="section-badge">Simple Process</div>
					<h2>How It Works</h2>
					<p>Getting started is easy. Follow these simple steps to begin your counseling journey.</p>
				</div>
				
				<div class="steps-grid">
				   <!-- Step 1 -->
					<div class="step-card">
						<div class="step-icon icon-blue">
							<i class="bi bi-person-plus-fill"></i>
							<span class="step-number">1</span>
						</div>
						<h4>Create Account</h4>
						<p>Register as a counselor or counselee. Fill in your profile with the necessary information.</p>
					</div>

					<!-- Step 2 -->
					<div class="step-card">
						<div class="step-icon icon-green">
							<i class="bi bi-person-check-fill"></i>
							<span class="step-number">2</span>
						</div>
						<h4>Choose Counselor</h4>
						<p>Browse our list of professional counselors and pick one that matches your needs.</p>
					</div>

					<!-- Step 3 -->
					<div class="step-card">
						<div class="step-icon icon-purple">
							<i class="bi bi-calendar-check-fill"></i>
							<span class="step-number">3</span>
						</div>
						<h4>Book Appointment</h4>
						<p>Select a convenient date and time and submit your appointment request instantly.</p>
					</div>

					<!-- Step 4 -->
					<div class="step-card">
						<div class="step-icon icon-orange">
							<i class="bi bi-chat-heart-fill"></i>
							<span class="step-number">4</span>
						</div>
						<h4>Get Support</h4>
						<p>Meet with your counselor and receive professional, confidential guidance and support.</p>
					</div>

				</div>
		</div>
	</div>
</div>
				
</section>

<section class="counsel-types">
    <div class="section-header">
        <div class="section-badge">Our Services</div>
        <h2>Areas We Cover</h2>
        <p>We offer a wide range of counseling specializations to address every aspect of your wellbeing.</p>
    </div>
    <div class="types-grid">
        <div class="type-card">
            <div class="type-icon" style="background:#e3f2fd; color:#1565c0;">
                <i class="bi bi-person-heart"></i>
            </div>
            <h4>Children</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#fce4ec; color:#880e4f;">
                <i class="bi bi-heart"></i>
            </div>
            <h4>Pre-Marital</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#e8f5e9; color:#1b5e20;">
                <i class="bi bi-book"></i>
            </div>
            <h4>Study</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#fff3e0; color:#e65100;">
                <i class="bi bi-briefcase"></i>
            </div>
            <h4>Work</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#f3e5f5; color:#4a148c;">
                <i class="bi bi-people"></i>
            </div>
            <h4>Family</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#e0f7fa; color:#006064;">
                <i class="bi bi-activity"></i>
            </div>
            <h4>Mental Health</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#efebe9; color:#4e342e;">
                <i class="bi bi-person-heart"></i>
            </div>
            <h4>Grief</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#fbe9e7; color:#bf360c;">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <h4>Relationship</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#f9fbe7; color:#33691e;">
                <i class="bi bi-leaf"></i>
            </div>
            <h4>Wellness</h4>
        </div>
        <div class="type-card">
            <div class="type-icon" style="background:#e8eaf6; color:#1a237e;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h4>Financial</h4>
        </div>
    </div>
</section>


<section class="features">
    <div class="section-header">
        <div class="section-badge">Why Choose Us</div>
        <h2>Everything You Need</h2>
        <p>Our platform is built with security, ease of use, and effectiveness in mind.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon" style="background:#e8eaf6; color:#1a237e;">
                <i class="bi bi-lock"></i>
            </div>
            <h4>100% Confidential</h4>
            <p>All sessions and personal information are kept strictly private and secure.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#e8f5e9; color:#1b5e20;">
                <i class="bi bi-calendar-check"></i>
            </div>
            <h4>Easy Scheduling</h4>
            <p>Book, reschedule, or cancel appointments with just a few clicks anytime.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#f3e5f5; color:#4a148c;">
                <i class="bi bi-mortarboard"></i>
            </div>
            <h4>Verified Professionals</h4>
            <p>All counselors are vetted and approved by our admin team before onboarding.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#fff3e0; color:#e65100;">
               <i class="bi bi-bell"></i>
            </div>
            <h4>Email Notifications</h4>
            <p>Get instant email alerts for appointment confirmations and reminders.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#e0f7fa; color:#006064;">
                <i class="bi bi-phone"></i>
            </div>
            <h4>Mobile Friendly</h4>
            <p>Access the platform from any device — desktop, tablet, or smartphone.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#fce4ec; color:#880e4f;">
                <i class="bi bi-headset"></i>
            </div>
            <h4>Admin Support</h4>
            <p>Our admin team is always available to help manage users and resolve issues.</p>
        </div>
    </div>
</section>


<section class="cta">
    <h2>Ready to Get Started?</h2>
    <p>Join hundreds of people already benefiting from professional counseling support on our platform.</p>
    <div class="cta-buttons">
        <a href="{{ route('counselor.register') }}" class="hero-btn hero-btn-counselor">
            <i class="fas fa-user-md"></i> Register as Counselor
        </a>
        <a href="{{ route('counselee.register') }}" class="hero-btn hero-btn-counselee">
            <i class="fas fa-user-plus"></i> Register as Counselee
        </a>
    </div>
</section>


<footer>
    <div class="footer-inner">
        <div class="footer-brand"><span>P2P</span> Appointment</div>
        <div class="footer-links">
            <a href="{{ route('counselor.login') }}">Counselor Login</a>
            <a href="{{ route('counselee.login') }}">Counselee Login</a>
            <a href="{{ route('counselor.register') }}">Register Counselor</a>
            <a href="{{ route('counselee.register') }}">Register Counselee</a>
            <a href="{{ route('admin.login') }}">Admin</a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} P2P Appointment System. All rights reserved.
    </div>
</footer>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Navbar shadow on scroll
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 20) {
        navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.12)';
    } else {
        navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.06)';
    }
});
</script>
</body>
</html>
