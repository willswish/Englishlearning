<?php
session_start();

// Database connection (Update with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "englishlearn";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is set
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Get user input
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password, user_type FROM users WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $storedPassword, $userType);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Verify password
        if ($inputPassword === $storedPassword) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $userType;
            $_SESSION['login_start_time'] = time();

            // Redirect based on user type
            if ($userType == 'admin') {
                header("Location: /English-Learning-main/admin/adminpage.php");
            } else if ($userType == 'student') {
                header("Location: /English-Learning-main/student/dashboardselect.php?username=" . urlencode($username) . "&user_type=" . urlencode($userType));

            }
            exit();
		} else {
            // Redirect to login page with error parameter
            header("Location: http://localhost/English-Learning-main/loginsignup_page.php?error=1");
            exit();
        }
    } else {
        // Redirect to login page with error parameter
        header("Location: http://localhost/English-Learning-main/loginsignup_page.php?error=1");
        exit();
    }
    $stmt->close();

}

$conn->close();
?>


<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

		<script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer init></script>
		<script type="text/javascript">
			window.tailwind.config = {
				darkMode: ['class'],
				theme: {
					extend: {
						colors: {
							border: 'hsl(var(--border))',
							input: 'hsl(var(--input))',
							ring: 'hsl(var(--ring))',
							background: 'hsl(var(--background))',
							foreground: 'hsl(var(--foreground))',
							primary: {
								DEFAULT: 'hsl(var(--primary))',
								foreground: 'hsl(var(--primary-foreground))'
							},
							secondary: {
								DEFAULT: 'hsl(var(--secondary))',
								foreground: 'hsl(var(--secondary-foreground))'
							},
							destructive: {
								DEFAULT: 'hsl(var(--destructive))',
								foreground: 'hsl(var(--destructive-foreground))'
							},
							muted: {
								DEFAULT: 'hsl(var(--muted))',
								foreground: 'hsl(var(--muted-foreground))'
							},
							accent: {
								DEFAULT: 'hsl(var(--accent))',
								foreground: 'hsl(var(--accent-foreground))'
							},
							popover: {
								DEFAULT: 'hsl(var(--popover))',
								foreground: 'hsl(var(--popover-foreground))'
							},
							card: {
								DEFAULT: 'hsl(var(--card))',
								foreground: 'hsl(var(--card-foreground))'
							},
						},
					}
				}
			}
		</script>
		<style type="text/tailwindcss">
			@layer base {
				:root {
					--background: 0 0% 100%;
--foreground: 224 71.4% 4.1%;
--card: 0 0% 100%;
--card-foreground: 224 71.4% 4.1%;
--popover: 0 0% 100%;
--popover-foreground: 224 71.4% 4.1%;
--primary: 262.1 83.3% 57.8%;
--primary-foreground: 210 20% 98%;
--secondary: 220 14.3% 95.9%;
--secondary-foreground: 220.9 39.3% 11%;
--muted: 220 14.3% 95.9%;
--muted-foreground: 220 8.9% 46.1%;
--accent: 220 14.3% 95.9%;
--accent-foreground: 220.9 39.3% 11%;
--destructive: 0 84.2% 60.2%;
--destructive-foreground: 210 20% 98%;
--border: 220 13% 91%;
--input: 220 13% 91%;
--ring: 262.1 83.3% 57.8%;
				}
				.dark {
					--background: 224 71.4% 4.1%;
--foreground: 210 20% 98%;
--card: 224 71.4% 4.1%;
--card-foreground: 210 20% 98%;
--popover: 224 71.4% 4.1%;
--popover-foreground: 210 20% 98%;
--primary: 263.4 70% 50.4%;
--primary-foreground: 210 20% 98%;
--secondary: 215 27.9% 16.9%;
--secondary-foreground: 210 20% 98%;
--muted: 215 27.9% 16.9%;
--muted-foreground: 217.9 10.6% 64.9%;
--accent: 215 27.9% 16.9%;
--accent-foreground: 210 20% 98%;
--destructive: 0 62.8% 30.6%;
--destructive-foreground: 210 20% 98%;
--border: 215 27.9% 16.9%;
--input: 215 27.9% 16.9%;
--ring: 263.4 70% 50.4%;
				}
			}
		</style>
  </head>
  <body>
    <div class="min-h-screen flex flex-col items-center justify-center bg-cover" style="background-image: url('./pics/backg.jpg'); color: white;">
  <div class="w-full max-w-md p-8 bg-card rounded-lg shadow-2xl transform transition-transform duration-300 hover:scale-105">
    <h1 class="text-4xl font-extrabold mb-6 text-center text-primary">Welcome Kids!</h1>
    <ul class="flex justify-around mb-6">
      <li>
        <button id="signup-tab" class="px-6 py-2 bg-secondary text-secondary-foreground rounded-full transition-colors duration-300 shadow-lg hover:shadow-xl">Sign Up</button>
      </li>
      <li>
        <button id="login-tab" class="px-6 py-2 bg-muted text-muted-foreground rounded-full transition-colors duration-300 shadow-lg hover:shadow-xl">Log In</button>
      </li>
    </ul>
    <div id="signup-form" class="space-y-4">
	<form action="sign-up.php" method="POST" enctype="multipart/form-data" class="space-y-4">
		<input type="text" name="username" placeholder="Username" class="w-full px-4 py-2 bg-input text-foreground rounded-full border border-border focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-300" required />
		<input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 bg-input text-foreground rounded-full border border-border focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-300" required />
		<input type="password" name="password" placeholder="Password" class="w-full px-4 py-2 bg-input text-foreground rounded-full border border-border focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-300" required />
		<input type="file" name="profile_image" class="w-full px-4 py-2 bg-input text-foreground rounded-full border border-border focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-300" />
		<button type="submit" class="w-full px-4 py-2 bg-primary text-primary-foreground rounded-full hover:bg-primary/80 transition-all duration-300">Sign Up</button>
	</form>
        </div>
	<div id="login-form" class="space-y-4 hidden">
		<form action="" method="POST" class="space-y-4">
			<input type="text" name="username" placeholder="Username" class="w-full px-4 py-2 bg-input text-foreground rounded-full border border-border focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-300" required />
			<input type="password" name="password" placeholder="Password" class="w-full px-4 py-2 bg-input text-foreground rounded-full border border-border focus:ring-2 focus:ring-primary focus:outline-none transition-all duration-300" required />
			<button type="submit" class="w-full px-4 py-2 bg-primary text-primary-foreground rounded-full hover:bg-primary/80 transition-all duration-300">Log In</button>
		</form>
	</div>
  </div>
</div>

<script>
	 // Show SweetAlert if `success` parameter is present
	 const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            Swal.fire({
                title: 'Registration Successful!',
                text: 'You can now log in.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Optionally redirect or perform another action
                }
            });
        } else if (urlParams.get('error') === '1') {
        Swal.fire({
            title: 'Invalid Credentials',
            text: 'Please check your username and password.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

  const signupTab = document.getElementById('signup-tab');
  const loginTab = document.getElementById('login-tab');
  const signupForm = document.getElementById('signup-form');
  const loginForm = document.getElementById('login-form');
  signupTab.addEventListener('click', () => {
    signupForm.classList.remove('hidden');
    loginForm.classList.add('hidden');
    signupTab.classList.add('bg-secondary', 'text-secondary-foreground', 'shadow-xl');
    signupTab.classList.remove('bg-muted', 'text-muted-foreground', 'shadow-lg');
    loginTab.classList.add('bg-muted', 'text-muted-foreground', 'shadow-lg');
    loginTab.classList.remove('bg-secondary', 'text-secondary-foreground', 'shadow-xl');
  });
  loginTab.addEventListener('click', () => {
    signupForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
    loginTab.classList.add('bg-secondary', 'text-secondary-foreground', 'shadow-xl');
    loginTab.classList.remove('bg-muted', 'text-muted-foreground', 'shadow-lg');
    signupTab.classList.add('bg-muted', 'text-muted-foreground', 'shadow-lg');
    signupTab.classList.remove('bg-secondary', 'text-secondary-foreground', 'shadow-xl');
  });
</script>

  </body>
</html>