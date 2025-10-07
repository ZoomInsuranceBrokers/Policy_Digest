<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reset-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
            padding: 1.5rem;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }
        .password-toggle:hover {
            color: #667eea;
        }
        .form-floating {
            position: relative;
        }
        .strength-meter {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }
        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        .strength-weak { background-color: #dc3545; width: 25%; }
        .strength-fair { background-color: #fd7e14; width: 50%; }
        .strength-good { background-color: #ffc107; width: 75%; }
        .strength-strong { background-color: #198754; width: 100%; }
        
        .password-requirements {
            font-size: 0.85rem;
            margin-top: 10px;
        }
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            color: #6c757d;
        }
        .requirement.met {
            color: #198754;
        }
        .requirement i {
            margin-right: 8px;
            width: 16px;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .back-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card reset-card">
                    <div class="card-header text-center">
                        <i class="fas fa-key fa-2x mb-2"></i>
                        <h4 class="mb-0">Reset Your Password</h4>
                        <p class="mb-0 mt-2 opacity-75">Create a new secure password</p>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0 list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                            
                            <!-- Email Display -->
                            <div class="mb-4">
                                <label for="email_display" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="email_display" 
                                       value="{{ $email }}" disabled>
                                <small class="text-muted">Password will be reset for this email address</small>
                            </div>
                            
                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>New Password
                                </label>
                                <div class="form-floating position-relative">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required
                                           autocomplete="new-password">
                                    <span class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-eye"></i>
                                    </span>
                                </div>
                                
                                <!-- Password Strength Meter -->
                                <div class="strength-meter">
                                    <div class="strength-bar" id="strength-bar"></div>
                                </div>
                                <small id="strength-text" class="text-muted"></small>
                                
                                <!-- Password Requirements -->
                                <div class="password-requirements">
                                    <div class="requirement" id="req-length">
                                        <i class="fas fa-times"></i>
                                        At least 8 characters long
                                    </div>
                                    <div class="requirement" id="req-uppercase">
                                        <i class="fas fa-times"></i>
                                        One uppercase letter
                                    </div>
                                    <div class="requirement" id="req-lowercase">
                                        <i class="fas fa-times"></i>
                                        One lowercase letter
                                    </div>
                                    <div class="requirement" id="req-number">
                                        <i class="fas fa-times"></i>
                                        One number
                                    </div>
                                </div>
                                
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirm New Password
                                </label>
                                <div class="form-floating position-relative">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           autocomplete="new-password">
                                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                    </span>
                                </div>
                                <small id="match-text" class="text-muted"></small>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-reset" id="submitBtn">
                                    <i class="fas fa-key me-2"></i>Reset Password
                                </button>
                            </div>
                        </form>
                        
                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="back-link">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Security Notice -->
                <div class="text-center mt-3">
                    <small class="text-white opacity-75">
                        <i class="fas fa-shield-alt me-1"></i>
                        Your password will be securely encrypted
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };

            // Update requirement indicators
            updateRequirement('req-length', requirements.length);
            updateRequirement('req-uppercase', requirements.uppercase);
            updateRequirement('req-lowercase', requirements.lowercase);
            updateRequirement('req-number', requirements.number);

            // Calculate strength
            Object.values(requirements).forEach(met => {
                if (met) strength++;
            });

            return { strength, requirements };
        }

        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            const icon = element.querySelector('i');
            
            if (met) {
                element.classList.add('met');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-check');
            } else {
                element.classList.remove('met');
                icon.classList.remove('fa-check');
                icon.classList.add('fa-times');
            }
        }

        function updateStrengthMeter(strength) {
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            
            bar.className = 'strength-bar';
            
            if (strength <= 1) {
                bar.classList.add('strength-weak');
                text.textContent = 'Weak password';
                text.className = 'text-danger';
            } else if (strength <= 2) {
                bar.classList.add('strength-fair');
                text.textContent = 'Fair password';
                text.className = 'text-warning';
            } else if (strength <= 3) {
                bar.classList.add('strength-good');
                text.textContent = 'Good password';
                text.className = 'text-info';
            } else {
                bar.classList.add('strength-strong');
                text.textContent = 'Strong password';
                text.className = 'text-success';
            }
        }

        // Password confirmation checker
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('match-text');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirmation.length === 0) {
                matchText.textContent = '';
                matchText.className = 'text-muted';
                return;
            }
            
            if (password === confirmation) {
                matchText.textContent = 'Passwords match';
                matchText.className = 'text-success';
                submitBtn.disabled = false;
            } else {
                matchText.textContent = 'Passwords do not match';
                matchText.className = 'text-danger';
                submitBtn.disabled = true;
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            const result = checkPasswordStrength(this.value);
            updateStrengthMeter(result.strength);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        // Form validation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            if (password !== confirmation) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            const result = checkPasswordStrength(password);
            if (result.strength < 3) {
                e.preventDefault();
                alert('Please choose a stronger password!');
                return;
            }
        });
    </script>
</body>
</html>
