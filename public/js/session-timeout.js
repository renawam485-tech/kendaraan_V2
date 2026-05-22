class SessionManager {
    constructor() {
        this.timeoutMinutes = 15;
        this.init();
    }
    
    init() {
        // Reset timer saat user berinteraksi dengan halaman
        const events = ['click', 'mousemove', 'keypress', 'scroll', 'touchstart'];
        events.forEach(event => {
            document.addEventListener(event, () => this.resetTimer());
        });
        
        // Start timer awal
        this.resetTimer();
        
        // Kirim heartbeat setiap 30 detik
        setInterval(() => this.sendHeartbeat(), 30000);
        
        console.log('Session Manager Started - Auto logout dalam 15 menit');
    }
    
    resetTimer() {
        // Hapus timer yang ada
        if (this.logoutTimer) clearTimeout(this.logoutTimer);
        if (this.warningTimer) clearTimeout(this.warningTimer);
        
        // Timer warning: muncul 1 menit sebelum logout (menit ke-14)
        this.warningTimer = setTimeout(() => this.showWarning(), 
            (this.timeoutMinutes - 1) * 60 * 1000);
        
        // Timer logout: pada menit ke-15
        this.logoutTimer = setTimeout(() => this.logout(), 
            this.timeoutMinutes * 60 * 1000);
        
        console.log('Timer direset, akan logout dalam 15 menit');
        
        // Kirim heartbeat ke server
        this.sendHeartbeat();
    }
    
    sendHeartbeat() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!token) return;
        
        fetch('/session/heartbeat', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            },
            keepalive: true
        }).catch(e => console.log('Heartbeat error:', e));
    }
    
    showWarning() {
        // Cegah warning ganda
        if (document.getElementById('session-warning')) return;
        
        const modal = document.createElement('div');
        modal.id = 'session-warning';
        modal.innerHTML = `
            <div style="position:fixed; top:0; left:0; width:100%; height:100%; 
                        background:rgba(0,0,0,0.5); z-index:99999;
                        display:flex; align-items:center; justify-content:center;">
                <div style="background:white; padding:30px; border-radius:8px; 
                            max-width:400px; text-align:center; box-shadow:0 4px 20px rgba(0,0,0,0.2);">
                    <h3 style="margin:0 0 15px 0; color:#f44336;">⚠️ Peringatan</h3>
                    <p>Sesi Anda akan berakhir dalam <strong id="countdown">60</strong> detik 
                       karena tidak ada aktivitas.</p>
                    <button onclick="window.extendSession()" 
                        style="background:#4CAF50; color:white; border:none;
                               padding:10px 30px; border-radius:5px; cursor:pointer;
                               font-size:16px; margin-top:15px;">
                        Tetap Login
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Countdown
        let seconds = 60;
        const countdownEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            seconds--;
            if (countdownEl) countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
            }
        }, 1000);
        
        // Fungsi extend session
        window.extendSession = () => {
            clearInterval(interval);
            modal.remove();
            this.resetTimer();
        };
    }
    
    logout() {
        console.log('Logout triggered - redirecting to login');
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            },
            keepalive: true
        }).finally(() => {
            window.location.href = '/login';
        });
    }
}

new SessionManager();