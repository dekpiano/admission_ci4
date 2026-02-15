<?= $this->extend('User/UserLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* Hero Section - Enhanced with Mascot Animation */
    .hero-section {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 25px;
        padding: 3rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        min-height: 350px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(to top, rgba(255, 255, 255, 0.1), transparent);
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Mascot Animation */
    .hero-mascot {
        position: relative;
        z-index: 3;
    }

    .mascot-image {
        max-width: 420px;
        width: 100%;
        height: auto;
        animation: float 3s ease-in-out infinite, fadeInUp 1s ease-out;
        filter: drop-shadow(0 20px 35px rgba(0, 0, 0, 0.25));
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(-2deg);
        }

        50% {
            transform: translateY(-15px) rotate(2deg);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Welcome Text Animation */
    .welcome-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        animation: slideInDown 0.8s ease-out;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-title {
        font-size: clamp(1.8rem, 5vw, 2.8rem);
        font-weight: 800;
        text-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    .hero-subtitle {
        font-size: clamp(0.9rem, 4vw, 1.3rem);
        font-weight: 600;
        opacity: 0.95;
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.8s ease-out 0.4s both;
        text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Typing Effect */
    .typing-text {
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        border-right: 3px solid white;
        animation: typing 3s steps(40) 1s forwards, blink 0.7s steps(1) infinite;
    }

    @keyframes typing {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }

    @keyframes blink {
        50% {
            border-color: transparent;
        }
    }

    /* Sparkles */
    .sparkle {
        position: absolute;
        width: 10px;
        height: 10px;
        background: white;
        border-radius: 50%;
        opacity: 0;
        animation: sparkle 2s ease-in-out infinite;
    }

    .sparkle:nth-child(1) {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .sparkle:nth-child(2) {
        top: 60%;
        left: 85%;
        animation-delay: 0.5s;
    }

    .sparkle:nth-child(3) {
        top: 30%;
        left: 70%;
        animation-delay: 1s;
    }

    .sparkle:nth-child(4) {
        top: 80%;
        left: 20%;
        animation-delay: 1.5s;
    }

    .sparkle:nth-child(5) {
        top: 15%;
        left: 50%;
        animation-delay: 0.8s;
    }

    @keyframes sparkle {

        0%,
        100% {
            opacity: 0;
            transform: scale(0);
        }

        50% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Decorative Bubbles */
    .bubble {
        position: absolute;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        animation: rise 8s ease-in infinite;
    }

    .bubble:nth-child(1) {
        width: 40px;
        height: 40px;
        left: 5%;
        animation-delay: 0s;
    }

    .bubble:nth-child(2) {
        width: 25px;
        height: 25px;
        left: 15%;
        animation-delay: 2s;
    }

    .bubble:nth-child(3) {
        width: 35px;
        height: 35px;
        left: 80%;
        animation-delay: 4s;
    }

    .bubble:nth-child(4) {
        width: 20px;
        height: 20px;
        left: 90%;
        animation-delay: 1s;
    }

    @keyframes rise {
        0% {
            bottom: -50px;
            opacity: 0;
        }

        50% {
            opacity: 0.6;
        }

        100% {
            bottom: 110%;
            opacity: 0;
        }
    }

    /* Hero Buttons Animation */
    .hero-buttons {
        animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    .hero-buttons .btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-size: 0.85rem;
        padding: 0.6rem 1rem;
    }

    .hero-buttons .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.4s, height 0.4s;
    }

    .hero-buttons .btn:hover::after {
        width: 200px;
        height: 200px;
    }

    .hero-buttons .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Hero Countdown Box - Prominent Design (Mobile First Optimized) */
    .hero-countdown-box {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 20px;
        padding: 1rem;
        /* Compact padding for mobile */
        border: 2px solid rgba(255, 255, 255, 0.5);
        animation: fadeInUp 0.8s ease-out 0.5s both, boxPulse 3s infinite alternate;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
        margin-top: 1rem;
        width: 100%;
        /* Ensure it fits container */
    }

    @keyframes boxPulse {
        0% {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 0 0 rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        100% {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15), 0 0 15px rgba(255, 255, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.8);
        }
    }

    .hero-countdown-box p {
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.8rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .hero-countdown-box .countdown-container ul {
        gap: 6px;
        /* Tight gap for mobile */
        justify-content: center;
        display: flex;
        flex-wrap: nowrap;
        /* Force single row if possible, or careful wrap */
    }

    .hero-countdown-box .countdown-container li {
        flex: 1;
        /* Distribute space evenly */
        min-width: 60px;
        /* Small enough for 320px screens */
        padding: 0.8rem 0.2rem;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 14px;
        color: #333;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .hero-countdown-box .countdown-container li::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        /* Thinner line for mobile */
        background: linear-gradient(90deg, #ff9eb5 0%, #84d2f6 100%);
    }

    .hero-countdown-box .countdown-container li span {
        font-size: 1.8rem;
        /* readable on mobile */
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
        margin-bottom: 2px;
        font-family: 'K2D', sans-serif;
    }

    .hero-countdown-box .countdown-container li .label {
        font-size: 0.65rem;
        margin-top: 0;
        color: #666;
        font-weight: 600;
        white-space: nowrap;
        /* Prevent breaking 'ชั่วโมง' */
    }

    /* Hero Status Badge - Mobile First */
    /* Hero Status Badge - Prominent Application Open Design */
    .hero-status-badge {
        display: inline-block;
        padding: 1.2rem 2.5rem;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1.5rem;
        animation: fadeInUp 0.8s ease-out 0.5s both;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 450px;
    }

    .hero-status-badge.open {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
        border: none;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        animation: fadeInUp 0.8s ease-out 0.5s both, pulseGreen 2s infinite;
    }

    .hero-status-badge.open i {
        font-size: 1.4em;
        vertical-align: middle;
        margin-right: 8px;
        animation: rotateSuccess 5s linear infinite;
    }

    @keyframes pulseGreen {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7);
        }

        70% {
            transform: scale(1.03);
            box-shadow: 0 0 0 20px rgba(52, 211, 153, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(52, 211, 153, 0);
        }
    }

    @keyframes rotateSuccess {
        0% {
            transform: rotate(0deg);
        }

        10% {
            transform: rotate(15deg);
        }

        20% {
            transform: rotate(-15deg);
        }

        30% {
            transform: rotate(10deg);
        }

        40% {
            transform: rotate(-10deg);
        }

        50% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .hero-status-badge.closed {
        background: rgba(245, 101, 101, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(245, 101, 101, 0.6);
        color: #e53e3e;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
    }

    /* Hero Date Info - Mobile First */
    .hero-date-info {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        animation: fadeInUp 0.8s ease-out 0.55s both;
    }

    .hero-date-info .date-item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.15);
    }

    .hero-date-info .date-item i {
        font-size: 0.9rem;
    }

    .hero-date-info .date-item.open i {
        color: #48BB78;
    }

    .hero-date-info .date-item.close i {
        color: #F56565;
    }

    /* Tablet (min-width: 576px) */
    @media (min-width: 576px) {
        .hero-buttons .btn {
            font-size: 0.95rem;
            padding: 0.8rem 1.5rem;
        }

        .hero-countdown-box {
            padding: 2rem;
            max-width: 550px;
            margin-left: 0;
            /* Align left or center depending on layout */
        }

        .hero-countdown-box p {
            font-size: 1.25rem;
        }

        .hero-countdown-box .countdown-container ul {
            gap: 20px;
        }

        .hero-countdown-box .countdown-container li {
            min-width: 90px;
            padding: 1.2rem 0.8rem;
        }

        .hero-countdown-box .countdown-container li span {
            font-size: 3rem;
        }

        .hero-countdown-box .countdown-container li .label {
            font-size: 0.85rem;
        }

        .hero-status-badge {
            font-size: 1.1rem;
            padding: 0.8rem 1.5rem;
            max-width: 300px;
        }

        .hero-date-info {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .hero-date-info .date-item {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }
    }

    /* Desktop (min-width: 992px) */
    @media (min-width: 992px) {
        .hero-buttons .btn {
            font-size: 1rem;
            padding: 1rem 1.8rem;
        }

        .hero-countdown-box {
            padding: 2rem 2.5rem;
            max-width: none;
            /* Allow full width if needed, or restrict */
        }

        .hero-countdown-box p {
            font-size: 1.4rem;
        }

        .hero-countdown-box .countdown-container ul {
            gap: 24px;
        }

        .hero-countdown-box .countdown-container li {
            min-width: 110px;
            padding: 1.5rem 1rem;
        }

        .hero-countdown-box .countdown-container li span {
            font-size: 4rem;
            margin-bottom: 8px;
        }

        .hero-countdown-box .countdown-container li .label {
            font-size: 0.95rem;
        }

        .hero-status-badge {
            font-size: 1.2rem;
            padding: 1rem 2rem;
            max-width: 350px;
        }

        .hero-status-badge.open {
            font-size: 1.4rem;
        }

        .hero-date-info .date-item {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .hero-date-info .date-item i {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 991px) {
        .hero-section {
            min-height: auto;
            padding: 2rem 1.5rem;
        }

        .mascot-image {
            max-width: 320px;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: 1.6rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 1.4rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .mascot-image {
            max-width: 160px;
        }
    }

    /* Status Check Alert Box - Premium Design */
    .status-alert-box {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(132, 210, 246, 0.3);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        animation: fadeInUp 0.8s ease-out 0.8s both;
        position: relative;
        overflow: hidden;
    }

    .status-alert-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 8px;
        height: 100%;
        background: linear-gradient(to bottom, #84d2f6, #ff9eb5);
    }

    .status-icon-pulse {
        width: 60px;
        height: 60px;
        background: #e0f2fe;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #0284c7;
        position: relative;
        flex-shrink: 0;
    }

    .status-icon-pulse::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: 50%;
        z-index: -1;
        animation: iconPulse 2s infinite;
        opacity: 0.4;
    }

    @keyframes iconPulse {
        0% {
            transform: scale(1);
            opacity: 0.4;
        }

        100% {
            transform: scale(1.8);
            opacity: 0;
        }
    }

    .status-text h5 {
        color: #0c4a6e;
        font-weight: 800;
        margin-bottom: 0.2rem;
    }

    .status-text p {
        color: #334155;
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .status-alert-box {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .status-alert-box .btn {
            width: 100%;
        }
    }

    /* Result Announcement Alert Box - Mega Prominent */
    .result-alert-box {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.35);
        color: white;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        animation: fadeInUp 0.8s ease-out 0.75s both, alertPulse 2s infinite alternate;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .result-alert-box::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    @keyframes alertPulse {
        from { transform: scale(1); }
        to { transform: scale(1.02); }
    }

    .result-icon-pulse {
        width: 65px;
        height: 65px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: white;
        position: relative;
        flex-shrink: 0;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }

    .result-text h4 {
        color: white;
        font-weight: 800;
        margin-bottom: 0.3rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .result-text p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
        font-size: 1.05rem;
    }

    .result-action .btn-white {
        background: white;
        color: #6366f1;
        font-weight: 700;
        border-radius: 50px;
        padding: 0.8rem 2rem;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }

    .result-action .btn-white:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        background: #f8f9fa;
        color: #4f46e5;
    }

    @media (max-width: 991px) {
        .result-alert-box {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .result-action {
            width: 100%;
        }

        .result-action .btn-white {
            width: 100%;
            justify-content: center;
        }
    }

    /* Report Alert Box - Golden/Green Success Design */
    .report-alert-box {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.35);
        color: white;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        animation: fadeInUp 0.8s ease-out 0.7s both, reportPulse 2s infinite alternate;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .report-alert-box::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    @keyframes reportPulse {
        from { transform: scale(1); }
        to { transform: scale(1.02); }
    }

    .report-icon-pulse {
        width: 65px;
        height: 65px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: white;
        position: relative;
        flex-shrink: 0;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }
    
    .report-text h4 {
        color: white;
        font-weight: 800;
        margin-bottom: 0.3rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .report-text p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
        font-size: 1.05rem;
    }

    .report-action .btn-white {
        background: white;
        color: #059669;
        font-weight: 700;
        border-radius: 50px;
        padding: 0.8rem 2rem;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
    }

    .report-action .btn-white:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        background: #f8f9fa;
        color: #047857;
    }

    @media (max-width: 991px) {
        .report-alert-box {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .report-action {
            width: 100%;
        }

        .report-action .btn-white {
            width: 100%;
            justify-content: center;
        }
    }

    /* Excellence Section - Mobile First */
    .excellence-section {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
    }

    .excellence-icon-wrapper {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
        box-shadow: 0 5px 15px rgba(255, 158, 181, 0.3);
    }

    .excellence-header h4 {
        color: #333;
        font-size: 0.95rem;
    }

    .excellence-header h4 i {
        color: #ff9eb5;
    }

    .excellence-header p {
        font-size: 0.8rem;
    }

    /* Excellence Cards - Mobile First */
    .excellence-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.8rem 0.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 100%;
        width: 100%;
        cursor: pointer;
        outline: none;
    }

    .excellence-card:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 158, 181, 0.3);
    }

    .excellence-card:active {
        transform: scale(0.95);
        background: white;
    }

    .excellence-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .excellence-card-icon.academic {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }

    .excellence-card-icon.sport {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    }

    .excellence-card-icon.art {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    }

    .excellence-card-icon.career {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    }

    .excellence-card-icon.language {
        background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
    }

    .excellence-card-title {
        font-size: 0.7rem;
        font-weight: 600;
        color: #333;
        text-align: center;
        line-height: 1.2;
    }

    /* Tablet and up (min-width: 576px) */
    @media (min-width: 576px) {
        .excellence-section {
            padding: 1.5rem;
            border-radius: 18px;
        }

        .excellence-icon-wrapper {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .excellence-header h4 {
            font-size: 1.1rem;
        }

        .excellence-header p {
            font-size: 0.85rem;
        }

        .excellence-card {
            padding: 1rem 0.6rem;
        }

        .excellence-card-icon {
            width: 45px;
            height: 45px;
            font-size: 1.3rem;
        }

        .excellence-card-title {
            font-size: 0.75rem;
        }
    }

    /* Desktop (min-width: 992px) */
    @media (min-width: 992px) {
        .excellence-section {
            padding: 2rem;
            border-radius: 20px;
        }

        .excellence-icon-wrapper {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
        }

        .excellence-header h4 {
            font-size: 1.25rem;
        }

        .excellence-header p {
            font-size: 0.9rem;
        }

        .excellence-card {
            padding: 1.2rem 0.8rem;
            border-radius: 15px;
        }

        .excellence-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: white;
        }

        .excellence-card:hover .excellence-card-icon {
            transform: scale(1.1);
        }

        .excellence-card:hover .excellence-card-title {
            color: #ff9eb5;
        }

        .excellence-card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            margin-bottom: 0.8rem;
        }

        .excellence-card-title {
            font-size: 0.85rem;
            line-height: 1.3;
        }
    }

    /* Announcement Card */
    .announcement-card {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(255, 158, 181, 0.3);
        position: relative;
        overflow: hidden;
    }

    .announcement-card::after {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Countdown */
    .countdown-container {
        font-family: 'Prompt', sans-serif;
        color: #fff;
        display: inline-block;
        text-align: center;
    }

    .countdown-container ul {
        padding: 0;
        margin: 0;
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .countdown-container li {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        list-style-type: none;
        padding: 1rem 0.75rem;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        min-width: 80px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .countdown-container li span {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .countdown-container li .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-top: 8px;
        opacity: 0.95;
        font-weight: 600;
    }

    /* Application Cards */
    .app-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border: 2px solid transparent;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .app-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #ff9eb5 0%, #84d2f6 100%);
    }

    .app-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        border-color: #ff9eb5;
    }

    .app-card.m4:hover {
        border-color: #84d2f6;
    }

    .app-card.m4::before {
        background: linear-gradient(90deg, #84d2f6 0%, #ff9eb5 100%);
    }

    .level-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(255, 158, 181, 0.3);
    }

    .app-card.m4 .level-badge {
        background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%);
        box-shadow: 0 4px 15px rgba(132, 210, 246, 0.3);
    }

    .apply-button {
        width: 100%;
        padding: 1rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        border: none;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .apply-button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .apply-button:hover::before {
        width: 300px;
        height: 300px;
    }

    .apply-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
    }

    /* Custom Button Colors - Pastel Pink/Blue */
    .btn-primary {
        background: linear-gradient(135deg, #ff9eb5 0%, #ffc4d6 100%);
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #ff89a8 0%, #ffb3c6 100%);
    }

    .btn-info {
        background: linear-gradient(135deg, #84d2f6 0%, #a8e0ff 100%);
        border: none;
        color: white;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #6ec6f0 0%, #95d9ff 100%);
    }

    /* Schedule Table */
    .schedule-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .schedule-header {
        background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%);
        color: white;
        padding: 1.5rem;
    }

    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background: linear-gradient(90deg, rgba(255, 158, 181, 0.1) 0%, rgba(132, 210, 246, 0.1) 100%);
        transform: scale(1.01);
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 1rem;
        }

        .countdown-container li {
            min-width: 60px;
            padding: 0.75rem 0.5rem;
        }

        .countdown-container li span {
            font-size: 1.5rem;
        }

        .app-card {
            margin-bottom: 1.5rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (!empty($systemStatus->onoff_comment)): ?>
<!-- Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.15);">
            <!-- Premium Header -->
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%); padding: 1.5rem 2rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class='bx bxs-megaphone text-white' style="font-size: 1.8rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0" id="announcementModalLabel">
                            <i class='bx bx-bell-ring me-1'></i> ประกาศแจ้งเตือน
                        </h5>
                        <small class="text-white-50">ข้อมูลสำคัญจากทางโรงเรียน</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body" style="padding: 2rem;">
                <div class="alert alert-warning border-0 mb-3" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 14px; padding: 1.25rem;">
                    <div class="d-flex align-items-start gap-3">
                        <i class='bx bx-info-circle text-warning' style="font-size: 1.5rem; margin-top: 2px;"></i>
                        <div class="announcement-content" style="font-size: 1rem; color: #78350f; line-height: 1.7;">
                            <?= nl2br(esc($systemStatus->onoff_comment)) ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer border-0" style="padding: 1rem 2rem 1.5rem;">
                <button type="button" class="btn btn-lg w-100" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #ff9eb5 0%, #f77062 100%); color: white; border-radius: 12px; font-weight: 600; padding: 0.8rem;">
                    <i class='bx bx-check me-1'></i> รับทราบ
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var announcementModal = new bootstrap.Modal(document.getElementById('announcementModal'));
        announcementModal.show();
    });
</script>
<?php endif; ?>

<!-- Hero Section -->
<div class="hero-section">
    <!-- Decorative Elements -->
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>

    <div class="container">
        <div class="row align-items-center">
            <!-- Mascot Image -->
            <div class="col-lg-5 text-center hero-mascot order-lg-1 order-2">
                <img src="<?= base_url('public/assets/images/student_welcome.png') ?>" alt="นักเรียนต้อนรับ"
                    class="mascot-image">
            </div>

            <!-- Welcome Text Content -->
            <div class="col-lg-7 text-center text-lg-start hero-content order-lg-2 order-1 mb-4 mb-lg-0">
                <div class="welcome-badge mb-3">
                    <i class='bx bxs-smile me-1'></i> ยินดีต้อนรับสู่ครอบครัว สกจ.
                </div>

                <h1 class="hero-title mb-2" style="line-height: 1.2;">
                    ระบบรับสมัครนักเรียนออนไลน์
                </h1>

                <div class="d-flex flex-wrap gap-2 mb-3 justify-content-center justify-content-lg-start animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="badge bg-white text-primary rounded-pill px-3 py-2 d-flex align-items-center shadow-sm">
                        <i class='bx bx-calendar-event me-1'></i> ปีการศึกษา <?= $checkYear->openyear_year ?? '-' ?>
                    </div>
                    <?php if (isset($checkYear->openyear_year) && $checkYear->openyear_year >= 2569): ?>
                        <div class="badge bg-warning text-dark rounded-pill px-3 py-2 d-flex align-items-center shadow-sm border-white border-2">
                            <i class='bx bxs-star me-1'></i> รอบที่ <?= $systemStatus->onoff_round ?? '1' ?>
                        </div>
                    <?php endif; ?>
                </div>

                <p class="hero-subtitle opacity-75 mb-4">
                    <i class='bx bx-building-house me-1'></i> โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
                </p>

                <?php
                // Check registration status
                $is_not_open = false;
                $is_closed = false;
                $is_open = false;

                if (isset($systemStatus->onoff_datetime_regis_open) && time() < strtotime($systemStatus->onoff_datetime_regis_open)) {
                    $is_not_open = true;
                } elseif (isset($systemStatus->onoff_datetime_regis_close) && time() > strtotime($systemStatus->onoff_datetime_regis_close)) {
                    $is_closed = true;
                } elseif (isset($systemStatus->onoff_regis) && $systemStatus->onoff_regis == 'on') {
                    $is_open = true;
                }
                ?>

                <?php if ($is_not_open): ?>
                    <!-- Countdown to Open -->
                    <div class="hero-countdown-box my-4">
                        <p class="mb-3">
                            <i class='bx bxs-time-five me-2 text-warning' style="font-size: 1.2em;"></i>
                            นับถอยหลังสู่การรับสมัคร
                        </p>
                        <div class="countdown-container" data-target="<?= $systemStatus->onoff_datetime_regis_open ?>">
                            <ul>
                                <li><span class="days">0</span>
                                    <div class="label">วัน</div>
                                </li>
                                <li><span class="hours">0</span>
                                    <div class="label">ชั่วโมง</div>
                                </li>
                                <li><span class="minutes">0</span>
                                    <div class="label">นาที</div>
                                </li>
                                <li><span class="seconds">0</span>
                                    <div class="label">วินาที</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php elseif ($is_open): ?>
                    <!-- Currently Open - Clickable to Scroll -->
                    <?php
                    $activeQuotas = [];
                    if (isset($quotas) && is_array($quotas)) {
                        foreach ($quotas as $q) {
                            if (isset($q->quota_status) && $q->quota_status == 'on') {
                                $activeQuotas[] = isset($q->quota_explain) ? $q->quota_explain : (isset($q->quota_name) ? $q->quota_name : 'N/A');
                            }
                        }
                    }
                    $quotaNames = !empty($activeQuotas) ? implode(', ', $activeQuotas) : 'รับสมัครนักเรียนใหม่';
                    ?>
                    <a href="#apply-section"
                        class="hero-status-badge open my-4 text-decoration-none smooth-scroll d-inline-flex flex-column justify-content-center align-items-center py-3 px-5 h-auto">
                        <span class="fs-6 fw-normal mb-1 opacity-75"><i class='bx bxs-check-circle me-1'></i>
                            เปิดรับสมัครแล้ว</span>
                        <span class="fs-3 fw-bolder text-white text-uppercase"
                            style="line-height: 1.2;"><?= $quotaNames ?></span>
                    </a>
                <?php elseif ($is_closed): ?>
                    <!-- Closed -->
                    <div class="hero-status-badge closed mb-3">
                        <i class='bx bx-x-circle me-1'></i> ปิดรับสมัครแล้ว
                    </div>
                <?php endif; ?>

                <!-- Date Info -->
                <?php if (isset($systemStatus->onoff_datetime_regis_open) || isset($systemStatus->onoff_datetime_regis_close)): ?>
                    <div class="hero-date-info d-flex flex-row flex-nowrap justify-content-center justify-content-lg-start gap-2 mb-3 overflow-auto"
                        style="white-space: nowrap;">
                        <?php if (isset($systemStatus->onoff_datetime_regis_open)): ?>
                            <div class="date-item open flex-fill text-center">
                                <i class='bx bx-calendar-check'></i>
                                <span>เปิด:
                                    <?= $datethai->thai_date_fullmonth(strtotime($systemStatus->onoff_datetime_regis_open)) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($systemStatus->onoff_datetime_regis_close)): ?>
                            <div class="date-item close flex-fill text-center">
                                <i class='bx bx-calendar-x'></i>
                                <span>ปิด:
                                    <?= $datethai->thai_date_fullmonth(strtotime($systemStatus->onoff_datetime_regis_close)) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="hero-buttons d-flex justify-content-center justify-content-lg-start gap-2 flex-wrap">
                    <a href="<?= base_url('new-admission/manual') ?>" class="btn btn-light btn-lg rounded-pill px-4">
                        <i class='bx bx-book-open me-2'></i> คู่มือการสมัคร
                    </a>
                    <a href="<?= base_url('new-admission/status') ?>"
                        class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class='bx bx-search-alt me-2'></i> ตรวจสอบสถานะ
                    </a>
                    <a href="<?= base_url('new-admission/statistics') ?>"
                        class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class='bx bx-bar-chart-alt-2 me-2'></i> สถิติการสมัคร
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Result Alert Box -->
<?php if (isset($systemStatus) && isset($systemStatus->onoff_system) && $systemStatus->onoff_system == 'on'): ?>
    <div class="result-alert-box">
        <div class="result-icon-pulse">
            <i class="bx bxs-megaphone"></i>
        </div>
        <div class="result-text flex-grow-1">
            <h4 class="mb-1">🎉 <?= $systemStatus->onoff_system_text ?? 'ประกาศผลการคัดเลือกแล้ว!' ?></h4>
            <p>ตรวจสอบรายชื่อผู้มีสิทธิ์สอบ รายชื่อผู้ผ่านการคัดเลือก และกำหนดการต่าง ๆ ได้ที่นี่ครับ</p>
        </div>
        <div class="result-action">
            <a href="<?= site_url('new-admission/announcements') ?>" class="btn btn-white shadow-sm">
                <i class="bx bx-show-alt me-1"></i> ดูรายละเอียดประกาศผล
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- Report Alert Box -->
<?php if (isset($systemStatus) && isset($systemStatus->onoff_report) && $systemStatus->onoff_report == 'on'): ?>
    <div class="report-alert-box">
        <div class="report-icon-pulse">
            <i class="bx bxs-user-check"></i>
        </div>
        <div class="report-text flex-grow-1">
            <h4 class="mb-1">✨ เปิดรายงานตัวนักเรียนแล้ว (รอบที่ <?= $systemStatus->onoff_round ?? '1' ?>)</h4>
            <p>ยินดีกับนักเรียนทุกคนที่ผ่านการคัดเลือกในรอบนี้ สามารถรายงานตัวและมอบตัวออนไลน์ได้ที่นี่ครับ</p>
        </div>
        <div class="report-action">
            <a href="<?= site_url('confirmation') ?>" class="btn btn-white shadow-sm">
                <i class="bx bx-check-double me-1"></i> เข้าสู่ระบบรายงานตัว
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- Status Check Alert Box -->
<div class="status-alert-box">
    <div class="status-icon-pulse">
        <i class="bx bx-bell"></i>
    </div>
    <div class="status-text flex-grow-1">
        <h5>สมัครเสร็จแล้ว? อย่าลืมเช็คสถานะนะ! 📋</h5>
        <p>เมื่อน้องๆ สมัครเสร็จแล้ว พี่ๆ แนะนำให้เข้ามาตรวจสอบสถานะ <strong>"บ่อยๆ"</strong> จนกว่าจะขึ้นว่า
            <strong><span class="text-success fw-bold">"ผ่านการตรวจสอบ"</span></strong> เพื่อรักษาสิทธิ์ของตนเองนะครับ
        </p>
    </div>
    <div class="status-action">
        <a href="<?= site_url('new-admission/status') ?>" class="btn btn-primary shadow-sm px-4 py-2">
            <i class="bx bx-search-alt-2 me-1"></i> ตรวจสอบสถานะตอนนี้
        </a>
    </div>
</div>

<!-- Excellence Section -->
<div class="excellence-section mb-4">
    <div class="excellence-header text-center mb-3">
        <div class="excellence-icon-wrapper mb-2">
            <i class='bx bx-star'></i>
        </div>
        <h4 class="fw-bold mb-2">
            <i class='bx bx-info-circle me-1'></i> กรุณาอ่านระเบียบการรับสมัครก่อนสมัคร
        </h4>
        <p class="text-muted mb-0">เลือกประเภทความเป็นเลิศที่ต้องการศึกษารายละเอียด</p>
    </div>

    <div class="row g-3 justify-content-center">
        <div class="col-6 col-md-4 col-lg">
            <button type="button" class="excellence-card" data-bs-toggle="modal" data-bs-target="#excellenceModal"
                data-title="ด้านวิชาการ" data-image="<?= base_url('public/assets/banner/academic.jpg') ?>">
                <div class="excellence-card-icon academic">
                    <i class='bx bx-book-reader'></i>
                </div>
                <span class="excellence-card-title">ด้านวิชาการ</span>
            </button>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <button type="button" class="excellence-card" data-bs-toggle="modal" data-bs-target="#excellenceModal"
                data-title="ด้านกีฬา" data-image="<?= base_url('public/assets/banner/sport.jpg') ?>">
                <div class="excellence-card-icon sport">
                    <i class='bx bx-football'></i>
                </div>
                <span class="excellence-card-title">ด้านกีฬา</span>
            </button>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <button type="button" class="excellence-card" data-bs-toggle="modal" data-bs-target="#excellenceModal"
                data-title="ด้านศิลปะ ดนตรี การแสดง" data-image="<?= base_url('public/assets/banner/art.jpg') ?>">
                <div class="excellence-card-icon art">
                    <i class='bx bx-palette'></i>
                </div>
                <span class="excellence-card-title">ด้านศิลปะ ดนตรี การแสดง</span>
            </button>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <button type="button" class="excellence-card" data-bs-toggle="modal" data-bs-target="#excellenceModal"
                data-title="ด้านวิชาชีพ" data-image="<?= base_url('public/assets/banner/career.jpg') ?>">
                <div class="excellence-card-icon career">
                    <i class='bx bx-briefcase'></i>
                </div>
                <span class="excellence-card-title">ด้านวิชาชีพ</span>
            </button>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <button type="button" class="excellence-card" data-bs-toggle="modal" data-bs-target="#excellenceModal"
                data-title="ด้านภาษา" data-image="<?= base_url('public/assets/banner/language.jpg') ?>">
                <div class="excellence-card-icon language">
                    <i class='bx bx-globe'></i>
                </div>
                <span class="excellence-card-title">ด้านภาษา</span>
            </button>
        </div>
    </div>
</div>

<!-- Excellence Modal -->
<div class="modal fade" id="excellenceModal" tabindex="-1" aria-labelledby="excellenceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content"
            style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.2);">
            <div class="modal-header"
                style="background: linear-gradient(135deg, #ff9eb5 0%, #84d2f6 100%); border: none; padding: 1.25rem 1.5rem;">
                <h5 class="modal-title text-white fw-bold" id="excellenceModalLabel">
                    <i class='bx bx-star me-2'></i><span id="modalTitleText">รายละเอียด</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <img id="excellenceImage" src="" alt="Excellence Image" class="w-100"
                    style="display: block; max-height: 80vh; object-fit: contain; background: #f8f9fa;">
            </div>
            <div class="modal-footer justify-content-center gap-2" style="border: none; background: #f8f9fa;">
                <button type="button" class="btn btn-primary rounded-pill px-4" id="zoomImageBtn">
                    <i class='bx bx-zoom-in me-1'></i> ซูมดูรูปภาพ
                </button>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class='bx bx-x me-1'></i> ปิด
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Image Modal (for zoom) -->
<div class="modal fade" id="fullscreenImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: rgba(0,0,0,0.95);">
            <div class="modal-header border-0 position-absolute w-100"
                style="z-index: 10; background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, transparent 100%);">
                <h5 class="modal-title text-white fw-bold">
                    <i class='bx bx-image me-2'></i><span id="fullscreenTitleText">รายละเอียด</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center p-0" id="zoomableContainer"
                style="overflow: auto; cursor: grab;">
                <img id="fullscreenImage" src="" alt="Fullscreen Image" class="zoomable-image"
                    style="max-width: none; max-height: none; transform-origin: center center; transition: transform 0.3s ease;">
            </div>
            <div class="modal-footer border-0 position-absolute w-100 bottom-0 justify-content-center"
                style="z-index: 10; background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, transparent 100%);">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-light rounded-start-pill px-3" id="zoomOutBtn">
                        <i class='bx bx-zoom-out'></i> ย่อ
                    </button>
                    <button type="button" class="btn btn-outline-light px-3" id="zoomResetBtn">
                        <i class='bx bx-reset'></i> รีเซ็ต
                    </button>
                    <button type="button" class="btn btn-outline-light rounded-end-pill px-3" id="zoomInBtn">
                        <i class='bx bx-zoom-in'></i> ขยาย
                    </button>
                </div>
                <button type="button" class="btn btn-light rounded-pill px-4 ms-3" data-bs-dismiss="modal">
                    <i class='bx bx-x me-1'></i> ปิด
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Application Cards -->
<div class="row justify-content-center g-4 mb-4" id="apply-section">
    <?php
    // --- Determine which levels are open based on active quotas ---
    $open_levels = [];
    if (!empty($quotas)) {
        foreach ($quotas as $quota) {
            if (isset($quota->quota_status) && $quota->quota_status == 'on' && !empty($quota->quota_level)) {
                $level_string = $quota->quota_level;
                $levels_in_quota = [];

                if (strpos($level_string, '|') !== false) {
                    $levels_in_quota = explode('|', $level_string);
                } elseif (strpos($level_string, ',') !== false) {
                    $levels_in_quota = explode(',', $level_string);
                } else {
                    $levels_in_quota = [$level_string];
                }

                foreach ($levels_in_quota as $level_str) {
                    $level_num_char = preg_replace('/[^0-9]/', '', $level_str);
                    if (is_numeric($level_num_char)) {
                        $open_levels[] = intval($level_num_char);
                    }
                }
            }
        }
    }
    $open_levels = array_unique($open_levels);
    sort($open_levels);
    ?>

    <?php if (isset($systemStatus) && $systemStatus->onoff_regis == 'on'): ?>
        <?php if (!empty($open_levels)): ?>
            <?php foreach ($open_levels as $level_num): ?>
                <?php
                $is_junior_high = $level_num <= 3;
                $pre_check_url_level = $is_junior_high ? '1' : '4';
                $subtitle = $is_junior_high ? 'สำหรับนักเรียนที่จบการศึกษาชั้น ป.6 หรือเทียบเท่า' : 'สำหรับนักเรียนที่จบการศึกษาชั้น ม.3 หรือเทียบเท่า';
                $btn_class = $is_junior_high ? 'btn-primary' : 'btn-info text-white';
                $card_class = $is_junior_high ? '' : 'm4';
                ?>
                <div class="col-md-6 col-lg-5">
                    <div class="app-card <?= $card_class ?>">
                        <div class="text-center">
                            <div class="level-badge">
                                <i class='bx bx-bookmark me-1'></i> มัธยมศึกษาปีที่ <?= $level_num ?>
                            </div>
                            <p class="text-muted mb-3"><?= $subtitle ?></p>
                            <div class="mb-4">
                                <i class='bx bx-calendar fs-1 text-primary mb-2'></i>
                                <p class="fw-bold text-dark mb-0">
                                    ปีการศึกษา <?= isset($checkYear->openyear_year) ? $checkYear->openyear_year : date('Y') + 543 ?>
                                </p>
                            </div>

                            <?php
                            $is_closed = false;
                            $is_not_open = false;
                            $open_time = 0;

                            if (isset($systemStatus->onoff_datetime_regis_open)) {
                                $open_time = strtotime($systemStatus->onoff_datetime_regis_open);
                                if (time() < $open_time) {
                                    $is_not_open = true;
                                }
                            }

                            if (isset($systemStatus->onoff_datetime_regis_close)) {
                                $close_time = strtotime($systemStatus->onoff_datetime_regis_close);
                                if (time() > $close_time) {
                                    $is_closed = true;
                                }
                            }
                            ?>

                            <?php if ($is_closed): ?>
                                <button class="apply-button btn btn-secondary" disabled>
                                    <i class='bx bx-x-circle me-2'></i> ปิดรับสมัครแล้ว
                                </button>
                            <?php elseif ($is_not_open): ?>
                                <button class="apply-button btn btn-warning" disabled>
                                    <i class='bx bx-time-five me-2'></i> ยังไม่ถึงวันรับสมัคร
                                </button>
                            <?php else: ?>
                                <button type="button" class="apply-button btn <?= $btn_class ?> apply-btn"
                                    data-href="<?= base_url('new-admission/pre-check/' . $pre_check_url_level . '?level=' . $level_num) ?>">
                                    <i class='bx bx-edit-alt me-2'></i> สมัครเรียน ม.<?= $level_num ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="announcement-card text-center">
                    <i class='bx bx-info-circle fs-1 mb-3'></i>
                    <h4 class="fw-bold">ยังไม่เปิดรับสมัคร</h4>
                    <p class="mb-0">ยังไม่มีระดับชั้นที่เปิดรับสมัครในขณะนี้ กรุณาติดตามประกาศจากทางโรงเรียน</p>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="col-12">
            <div class="announcement-card text-center">
                <i class='bx bx-lock-alt fs-1 mb-3'></i>
                <h4 class="fw-bold">ปิดระบบรับสมัคร</h4>
                <p class="mb-0">ระบบรับสมัครนักเรียนออนไลน์ยังไม่เปิดให้บริการ กรุณาติดตามประกาศจากทางโรงเรียน</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Schedule Table -->
<div class="row g-4">
    <?php
    $grouped_schedules = [];
    if (!empty($schedules)) {
        foreach ($schedules as $schedule) {
            $level = $schedule->schedule_level;
            if (!isset($grouped_schedules[$level])) {
                $grouped_schedules[$level] = [];
            }
            $grouped_schedules[$level][] = $schedule;
        }
    }
    ?>

    <?php if (!empty($grouped_schedules)): ?>
        <?php foreach ($grouped_schedules as $level => $level_schedules): ?>
            <div class="col-md-12">
                <div class="schedule-card h-100">
                    <div class="schedule-header">
                        <h5 class="mb-0 fw-bold"><i class='bx bx-calendar-event me-2'></i> กำหนดการ: <?= $level ?></h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th width="25%"><i class='bx bx-bookmark me-2'></i> รอบการรับสมัคร</th>
                                    <th width="20%"><i class='bx bx-edit me-2'></i> รับสมัคร</th>
                                    <th width="15%"><i class='bx bx-pencil me-2'></i> สอบ</th>
                                    <th width="20%"><i class='bx bx-broadcast me-2'></i> ประกาศผล</th>
                                    <th width="20%"><i class='bx bx-id-card me-2'></i> รายงานตัว</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($level_schedules as $schedule): ?>
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="fw-bold text-primary"><?= $schedule->schedule_round ?></div>
                                        </td>

                                        <!-- Recruit -->
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_recruit_start)) ?>
                                                    -
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_recruit_end)) ?>
                                                </span>
                                                <small class="text-muted" style="font-size: 0.75rem;">Online</small>
                                            </div>
                                        </td>

                                        <!-- Exam -->
                                        <td class="align-middle text-center">
                                            <?php if ($schedule->schedule_exam): ?>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_exam)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Announce -->
                                        <td class="align-middle text-center">
                                            <?php if ($schedule->schedule_announce): ?>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_announce)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Report -->
                                        <td class="align-middle text-center">
                                            <?php if ($schedule->schedule_report): ?>
                                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                                    <?= $datethai->thai_date_short(strtotime($schedule->schedule_report)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="schedule-card">
                <div class="schedule-header">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-calendar-event me-2'></i> กำหนดการรับสมัคร</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class='bx bx-calendar-x fs-1 text-muted mb-3'></i>
                                        <h5 class="fw-bold text-secondary">ยังไม่มีกำหนดการ</h5>
                                        <p class="text-muted mb-0">กรุณาติดตามประกาศจากทางโรงเรียนในภายหลัง</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Contact Us Section -->
<div class="row mt-5 mb-4">
    <div class="col-12">
        <div class="excellence-section p-4 p-md-5" style="border-radius: 30px; background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%); backdrop-filter: blur(10px); border: 1px solid rgba(255, 158, 181, 0.2); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
                    <div class="welcome-badge mb-3 bg-label-primary px-3 py-1 rounded-pill d-inline-block" style="background: rgba(255, 158, 181, 0.1) !important; color: #ff9eb5 !important;">
                        <span class="small fw-bold text-uppercase"><i class="bx bx-headphone me-1"></i> Support Center</span>
                    </div>
                    <h2 class="fw-bold mb-3 display-6" style="color: #444;">ติดต่อสอบถามข้อมูล</h2>
                    <p class="text-muted mb-4 fs-5">หากคุณมีข้อสงสัยหรือต้องการความช่วยเหลือเกี่ยวกับการสมัครเรียน สามารถติดต่อเราได้ผ่านช่องทางต่างๆ หรือเยี่ยมชมหน้าช่วยเหลือของเรา</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="<?= base_url('contact') ?>" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                            <i class="bx bx-help-circle me-2"></i> ดูคำถามที่พบบ่อย (FAQ)
                        </a>
                        <a href="https://line.me/R/ti/p/<?= esc($contact_info['line_id'] ?? '') ?>" target="_blank" class="btn btn-outline-success btn-lg rounded-pill px-4 shadow-sm" style="border-color: #00c300; color: #008f00;">
                            <i class="bi bi-line me-2"></i> LINE Official
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-none bg-transparent">
                        <div class="card-body p-0">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-4 bg-white border h-100 transition-hover shadow-sm" style="transition: all 0.3s ease;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-3 bg-label-info p-2 me-3">
                                                <i class="bx bx-phone fs-4 text-info"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">เบอร์โทรศัพท์</h6>
                                        </div>
                                        <p class="mb-0 small text-muted">โรงเรียน: <?= esc($contact_info['phone'] ?? '') ?></p>
                                        <p class="mb-0 small text-muted"><?= esc($contact_info['phone2'] ?? '') ?></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-4 bg-white border h-100 transition-hover shadow-sm" style="transition: all 0.3s ease;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-3 bg-label-primary p-2 me-3">
                                                <i class="bx bxl-facebook fs-4 text-primary"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">Facebook</h6>
                                        </div>
                                        <p class="mb-0 small text-muted">SKJNS160</p>
                                        <a href="<?= esc($contact_info['facebook'] ?? '') ?>" target="_blank" class="small text-primary text-decoration-none">เยี่ยมชมเพจ <i class="bx bx-right-arrow-alt"></i></a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-4 bg-white border h-100 transition-hover shadow-sm" style="transition: all 0.3s ease;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-3 bg-label-warning p-2 me-3">
                                                <i class="bx bx-time fs-4 text-warning"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">เวลาทำการ</h6>
                                        </div>
                                        <p class="mb-0 small text-muted"><?= esc($contact_info['office_hours'] ?? '') ?></p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-4 bg-white border h-100 transition-hover shadow-sm" style="transition: all 0.3s ease;">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-3 bg-label-secondary p-2 me-3">
                                                <i class="bx bx-map fs-4 text-secondary"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">สถานที่ตั้ง</h6>
                                        </div>
                                        <a href="https://maps.google.com/?q=โรงเรียนสวนกุหลาบวิทยาลัย(จิรประวัติ)นครสวรรค์" target="_blank" class="small text-danger text-decoration-none">เปิดแผนที่ Google Maps <i class="bx bx-navigation"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover:hover {
        transform: translateY(-5px);
        border-color: #ff9eb5 !important;
        box-shadow: 0 10px 20px rgba(255, 158, 181, 0.1) !important;
    }
</style>

<!-- PDPA Modal -->
<div class="modal fade" id="pdpaModal" tabindex="-1" aria-labelledby="pdpaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdpaModalLabel">ข้อตกลงและเงื่อนไขการใช้ข้อมูลส่วนบุคคล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ข้อตกลงการใช้ข้อมูลส่วนบุคคลในการลงทะเบียนและสมัครเข้าศึกษาต่อในระบบรับสมัครออนไลน์ของโรงเรียนสวนกุหลาบวิทยาลัย
                        (จิรประวัติ) นครสวรรค์</strong></p>
                <p>โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์ ("โรงเรียน")
                    ให้ความสำคัญกับการคุ้มครองข้อมูลส่วนบุคคลของผู้สมัคร ("ท่าน")
                    โรงเรียนจึงได้จัดทำข้อตกลงและเงื่อนไขการใช้ข้อมูลส่วนบุคคลฉบับนี้ขึ้น
                    เพื่อแจ้งให้ท่านทราบถึงวิธีการที่โรงเรียนเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูลส่วนบุคคลของท่าน
                    และสิทธิของท่านในฐานะเจ้าของข้อมูลส่วนบุคคล</p>

                <h6>1. ข้อมูลส่วนบุคคลที่เก็บรวบรวม</h6>
                <p>โรงเรียนจะเก็บรวบรวมข้อมูลส่วนบุคคลของท่านที่จำเป็นต่อการรับสมัครและการพิจารณาคัดเลือกเข้าศึกษาต่อ
                    ซึ่งรวมถึงแต่ไม่จำกัดเพียง:</p>
                <ul>
                    <li>ข้อมูลระบุตัวตน เช่น ชื่อ-นามสกุล, เลขประจำตัวประชาชน, วันเดือนปีเกิด</li>
                    <li>ข้อมูลการติดต่อ เช่น ที่อยู่, หมายเลขโทรศัพท์, อีเมล</li>
                    <li>ข้อมูลการศึกษา เช่น ประวัติการศึกษา, ผลการเรียน</li>
                    <li>ข้อมูลผู้ปกครอง</li>
                    <li>ข้อมูลอื่นๆ ที่ท่านให้ไว้ในใบสมัคร</li>
                </ul>

                <h6>2. วัตถุประสงค์ในการเก็บรวบรวม ใช้ หรือเปิดเผยข้อมูล</h6>
                <p>โรงเรียนจะใช้ข้อมูลส่วนบุคคลของท่านเพื่อวัตถุประสงค์ดังต่อไปนี้:</p>
                <ul>
                    <li>เพื่อดำเนินการตามกระบวนการรับสมัคร และตรวจสอบคุณสมบัติของผู้สมัคร</li>
                    <li>เพื่อใช้ในการติดต่อสื่อสารกับท่านและผู้ปกครองเกี่ยวกับการสมัคร</li>
                    <li>เพื่อใช้ในการพิจารณาคัดเลือกนักเรียนเข้าศึกษาต่อ</li>
                    <li>เพื่อจัดทำทะเบียนนักเรียน และใช้ในกิจกรรมที่เกี่ยวข้องกับการศึกษาของโรงเรียน
                        (กรณีที่ท่านผ่านการคัดเลือก)</li>
                    <li>เพื่อปฏิบัติตามกฎหมายและข้อบังคับที่เกี่ยวข้อง</li>
                </ul>

                <h6>3. การเปิดเผยข้อมูลส่วนบุคคล</h6>
                <p>โรงเรียนจะไม่เปิดเผยข้อมูลส่วนบุคคลของท่านแก่บุคคลภายนอกโดยไม่ได้รับความยินยอมจากท่าน
                    เว้นแต่ในกรณีที่มีกฎหมายกำหนดให้สามารถกระทำได้</p>

                <h6>4. ระยะเวลาในการเก็บรักษาข้อมูล</h6>
                <p>โรงเรียนจะเก็บรักษาข้อมูลส่วนบุคคลของท่านไว้เป็นระยะเวลาเท่าที่จำเป็นเพื่อบรรลุวัตถุประสงค์ที่ได้แจ้งไว้
                    และตามที่กฏหมายกำหนด</p>

                <h6>5. สิทธิของเจ้าของข้อมูล</h6>
                <p>ท่านมีสิทธิตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 ซึ่งรวมถึงสิทธิในการขอเข้าถึง ขอแก้ไข
                    ขอให้ลบ หรือจำกัดการใช้ข้อมูลส่วนบุคคลของท่าน</p>

                <p class="mt-4">การที่ท่านกดปุ่ม "ยอมรับ" และดำเนินการสมัครต่อไป
                    ถือว่าท่านได้อ่านและเข้าใจข้อความข้างต้นโดยละเอียด และยินยอมให้โรงเรียนเก็บรวบรวม ใช้
                    และเปิดเผยข้อมูลส่วนบุคคลของท่านตามวัตถุประสงค์ที่ระบุไว้ในข้อตกลงนี้ทุกประการ</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ไม่ยอมรับ</button>
                <button type="button" class="btn btn-primary" id="pdpa-accept-btn">ยอมรับและดำเนินการต่อ</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const countdowns = document.querySelectorAll('.countdown-container');

        countdowns.forEach(timer => {
            const targetDateStr = timer.dataset.target;
            if (!targetDateStr) return;

            const targetDate = new Date(targetDateStr.replace(' ', 'T')).getTime();

            const daysEl = timer.querySelector('.days');
            const hoursEl = timer.querySelector('.hours');
            const minutesEl = timer.querySelector('.minutes');
            const secondsEl = timer.querySelector('.seconds');

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    daysEl.textContent = '0';
                    hoursEl.textContent = '0';
                    minutesEl.textContent = '0';
                    secondsEl.textContent = '0';
                    setTimeout(() => location.reload(), 1000);
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                daysEl.textContent = days;
                hoursEl.textContent = hours.toString().padStart(2, '0');
                minutesEl.textContent = minutes.toString().padStart(2, '0');
                secondsEl.textContent = seconds.toString().padStart(2, '0');
            }

            updateCountdown();
            const countdownInterval = setInterval(updateCountdown, 1000);
        });

        // PDPA Modal
        const applyButtons = document.querySelectorAll('.apply-btn');
        const pdpaModal = new bootstrap.Modal(document.getElementById('pdpaModal'));
        let selectedHref = '';

        applyButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                selectedHref = this.dataset.href;
                pdpaModal.show();
            });
        });

        document.getElementById('pdpa-accept-btn').addEventListener('click', function () {
            if (selectedHref) {
                window.location.href = selectedHref;
            }
        });

        // Excellence Modal - Set title and image dynamically
        const excellenceModal = document.getElementById('excellenceModal');
        let currentImageSrc = '';
        let currentTitle = '';

        if (excellenceModal) {
            excellenceModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                currentTitle = button.getAttribute('data-title');
                currentImageSrc = button.getAttribute('data-image');

                document.getElementById('modalTitleText').textContent = currentTitle;
                document.getElementById('excellenceImage').src = currentImageSrc;
                document.getElementById('excellenceImage').alt = currentTitle;
            });
        }

        // Fullscreen Zoom Modal
        const zoomImageBtn = document.getElementById('zoomImageBtn');
        const fullscreenModal = document.getElementById('fullscreenImageModal');
        const fullscreenImage = document.getElementById('fullscreenImage');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const zoomResetBtn = document.getElementById('zoomResetBtn');
        const zoomableContainer = document.getElementById('zoomableContainer');

        let currentZoom = 1;
        const zoomStep = 0.25;
        const minZoom = 0.5;
        const maxZoom = 4;

        if (zoomImageBtn) {
            zoomImageBtn.addEventListener('click', function () {
                // Hide excellence modal and show fullscreen modal
                const excellenceModalInstance = bootstrap.Modal.getInstance(excellenceModal);
                excellenceModalInstance.hide();

                // Set fullscreen modal content
                document.getElementById('fullscreenTitleText').textContent = currentTitle;
                fullscreenImage.src = currentImageSrc;
                fullscreenImage.alt = currentTitle;

                // Reset zoom
                currentZoom = 1;
                updateZoom();

                // Show fullscreen modal
                const fullscreenModalInstance = new bootstrap.Modal(fullscreenModal);
                fullscreenModalInstance.show();
            });
        }

        // Zoom controls
        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', function () {
                if (currentZoom < maxZoom) {
                    currentZoom += zoomStep;
                    updateZoom();
                }
            });
        }

        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', function () {
                if (currentZoom > minZoom) {
                    currentZoom -= zoomStep;
                    updateZoom();
                }
            });
        }

        if (zoomResetBtn) {
            zoomResetBtn.addEventListener('click', function () {
                currentZoom = 1;
                updateZoom();
            });
        }

        function updateZoom() {
            if (fullscreenImage) {
                fullscreenImage.style.transform = `scale(${currentZoom})`;
            }
        }

        // Drag to pan functionality
        let isDragging = false;
        let startX, startY, scrollLeft, scrollTop;

        if (zoomableContainer) {
            zoomableContainer.addEventListener('mousedown', function (e) {
                isDragging = true;
                zoomableContainer.style.cursor = 'grabbing';
                startX = e.pageX - zoomableContainer.offsetLeft;
                startY = e.pageY - zoomableContainer.offsetTop;
                scrollLeft = zoomableContainer.scrollLeft;
                scrollTop = zoomableContainer.scrollTop;
            });

            zoomableContainer.addEventListener('mouseleave', function () {
                isDragging = false;
                zoomableContainer.style.cursor = 'grab';
            });

            zoomableContainer.addEventListener('mouseup', function () {
                isDragging = false;
                zoomableContainer.style.cursor = 'grab';
            });

            zoomableContainer.addEventListener('mousemove', function (e) {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - zoomableContainer.offsetLeft;
                const y = e.pageY - zoomableContainer.offsetTop;
                const walkX = (x - startX) * 2;
                const walkY = (y - startY) * 2;
                zoomableContainer.scrollLeft = scrollLeft - walkX;
                zoomableContainer.scrollTop = scrollTop - walkY;
            });

            // Mouse wheel zoom
            zoomableContainer.addEventListener('wheel', function (e) {
                e.preventDefault();
                if (e.deltaY < 0) {
                    // Zoom in
                    if (currentZoom < maxZoom) {
                        currentZoom += zoomStep;
                        updateZoom();
                    }
                } else {
                    // Zoom out
                    if (currentZoom > minZoom) {
                        currentZoom -= zoomStep;
                        updateZoom();
                    }
                }
            });
        }

        // Smooth Scroll for Status Badge
        document.querySelector('.smooth-scroll')?.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        // Reset zoom when fullscreen modal is hidden
        if (fullscreenModal) {
            fullscreenModal.addEventListener('hidden.bs.modal', function () {
                currentZoom = 1;
                updateZoom();
            });
        }
    });
</script>
<?= $this->endSection() ?>