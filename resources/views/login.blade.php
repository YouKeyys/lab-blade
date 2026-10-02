<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lab Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gradient-to-b from-[#457987] to-[#111E21] min-h-screen p-4 flex justify-center items-center font-sans text-black">

    <div class="flex w-full max-w-[850px] min-h-[500px] bg-white shadow-2xl rounded-xl overflow-hidden flex-col md:flex-row">
        
        <!-- Left Side (Gambar) -->
        <div class="hidden md:block md:flex-1 relative overflow-hidden">
            <img src="{{ asset('assets/images/callabzz.jpg') }}" alt="Lab View" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-black/20 to-[#295F43]/40"></div>
        </div>

        <!-- Right Side (Form) -->
        <div class="flex-1 p-10 flex flex-col justify-center bg-white relative">
            <h2 class="text-2xl font-bold text-[#1a3a32] mb-8 text-center">Welcome Back</h2>
            
            <form onsubmit="handleLogin(event)" class="space-y-5">
                <div class="flex flex-col gap-2">
                    <label class="font-bold text-gray-700 text-sm">Email</label>
                    <input type="email" id="email" placeholder="Enter your Email here" required 
                        class="w-full p-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-md focus:ring-2 focus:ring-[#42745d] outline-none transition-all">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="font-bold text-gray-700 text-sm">Password</label>
                    <div class="relative">
                        <input type="password" id="password" placeholder="Enter your Password here" required 
                            class="w-full p-3 pr-12 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-md focus:ring-2 focus:ring-[#42745d] outline-none transition-all">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- REMEMBER ME CHECKBOX -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" id="remember_me" class="w-4 h-4 text-[#42745d] border-gray-300 rounded focus:ring-[#42745d] cursor-pointer">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" id="btn-login" 
                    class="w-full md:w-40 mx-auto block py-3 mt-4 bg-[#42745d] text-white font-semibold rounded-md hover:bg-[#355e4b] transition-colors shadow-lg active:scale-95">
                    Log In
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                Back to the Public Dashboard? 
                <span onclick="window.location.href='/'" class="text-blue-600 hover:underline font-medium cursor-pointer ml-1">click here</span>
            </p>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // 1. AUTO-FILL & AUTO-REDIRECT SAAT HALAMAN DIBUKA
        document.addEventListener('DOMContentLoaded', () => {
            // Cek apakah user sebenarnya masih punya sesi aktif (mencegah akses ke halaman login)
            const savedRole = localStorage.getItem('userRole');
            if (savedRole === 'admin') {
                window.location.href = '/admin';
                return;
            } else if (savedRole === 'supervisor') {
                window.location.href = '/supervisor';
                return;
            }

            // Cek apakah ada email yang disimpan (Remember Me)
            const savedEmail = localStorage.getItem('remembered_email');
            if (savedEmail) {
                document.getElementById('email').value = savedEmail;
                document.getElementById('remember_me').checked = true;
                
                // Fokuskan kursor ke password setelah UI selesai dimuat
                setTimeout(() => {
                    document.getElementById('password').focus();
                }, 100);
            }
        });

        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwd.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        async function handleLogin(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-login');
            const originalText = btn.innerText;
            btn.innerText = 'Checking...';
            btn.disabled = true;

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.getElementById('remember_me').checked;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password }),
                });
                
                const data = await response.json();

                if (response.ok) {
                    // Simpan data sesi
                    localStorage.setItem('userRole', data.user.role);
                    localStorage.setItem('username', data.user.username);
                    localStorage.setItem('userId', data.user.id);
                    localStorage.setItem('userEmail', data.user.email);
                    
                    // LOGIKA REMEMBER ME
                    if (rememberMe) {
                        localStorage.setItem('remembered_email', email);
                    } else {
                        localStorage.removeItem('remembered_email');
                    }
                    
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Login Successful!', 
                        text: `Welcome back, ${data.user.username}`,
                        timer: 1500, 
                        showConfirmButton: false 
                    }).then(() => {
                        if (data.user.role === 'admin') {
                            window.location.href = '/admin';
                        } else {
                            window.location.href = '/supervisor';
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Login Failed', text: data.message || 'Invalid email or password.' });
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error(error);
                Swal.fire({ icon: 'error', title: 'Connection Error', text: 'Could not connect to the server.' });
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>