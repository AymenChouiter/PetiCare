<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PetiCare Admin Login</title>
  <link rel="stylesheet" href="/css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet" />
</head>

<body class="auth-page">
  <main class="auth-wrapper">
    <section class="auth-card">
      <div class="auth-card__brand">
        <img src="/assets/logo.png" alt="PetiCare logo" class="auth-card__logo" />
        <h1 class="auth-card__title">PetiCare Admin</h1>
        <p class="auth-card__desc">Sign in to access the dashboard.</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="auth-card__alert" role="alert">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form action="/login" method="post" class="auth-form">
        <div class="form-group">
          <label for="identity">Username or email</label>
          <input type="text" name="identity" id="identity" autocomplete="username" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" autocomplete="current-password" required />
        </div>
        <button type="submit" class="btn btn--primary auth-form__submit">Login</button>
      </form>
    </section>
  </main>
</body>

</html>

