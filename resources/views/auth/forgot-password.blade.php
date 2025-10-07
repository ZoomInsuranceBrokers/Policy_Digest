<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="10" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="15" fill="rgba(255,255,255,0.05)"/><circle cx="70" cy="20" r="8" fill="rgba(255,255,255,0.08)"/></svg>');
            backdrop-filter: blur(100px);
            z-index: -1;
        }
        
        .reset-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 60px 50px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .reset-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 40px;
            letter-spacing: -0.5px;
        }
        
        .input-group {
            margin-bottom: 30px;
            text-align: left;
        }
        
        .input-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #666;
            margin-bottom: 8px;
        }
        
        .input-field {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            outline: none;
        }
        
        .input-field:focus {
            border-color: #667eea;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .input-field.error {
            border-color: #e74c3c;
        }
        
        .reset-button {
            width: 100%;
            padding: 16px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .reset-button:hover {
            background: #5a6fd8;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .reset-button:active {
            transform: translateY(0);
        }
        
        .back-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #5a6fd8;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            color: #155724;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #721c24;
        }
        
        .alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .alert li {
            margin-bottom: 5px;
        }
        
        .alert li:last-child {
            margin-bottom: 0;
        }
        
        @media (max-width: 480px) {
            .reset-container {
                padding: 40px 30px;
                margin: 20px;
                border-radius: 16px;
            }
            
            .reset-title {
                font-size: 22px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1 class="reset-title">Reset Password</h1>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group">
                <label for="email" class="input-label">Email</label>
                <input type="email" 
                       class="input-field @error('email') error @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email address"
                       required>
            </div>
            
            <button type="submit" class="reset-button">
                Send Reset Link
            </button>
        </form>
        
        <a href="{{ route('login') }}" class="back-link">
            ← Back to Login
        </a>
    </div>
</body>
</html>
