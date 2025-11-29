<!-- <h1 class="bg-[url('./images/ban.jpg')] bg-cover bg-center w-full h-96"></h1> -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MarketHub - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#504B38',
            secondary: '#B9B28A',
            background: '#F8F3D9',
            accent: '#EBE5C2',
          }
        }
      }
    }
    module.exports = {
      theme: {
        extend: {
          backgroundImage: {

            'hero-pattern': "url('/img/hero-pattern.svg')",

            'footer-texture': "url('/img/footer-texture.png')",
          }
        }
      }
    }
  </script>
</head>

<body class="bg-background text-primary">

  <!-- Header -->
  <nav class="bg-white shadow-lg rounded-2xl mx-6 mt-2 px-6 py-4 flex items-center justify-between">
    <div class="text-2xl font-bold text-primary">MarketHub</div>

    <div class="flex-1 mx-8">
      <input type="text" placeholder="Search products..."
        class="w-full p-2 rounded-full border border-accent focus:ring-2 focus:ring-secondary outline-none">
    </div>

    <div class="flex items-center space-x-4">
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile" class="w-10 h-10 rounded-full">
      <a href="login.php" class="bg-primary text-white px-4 py-2 rounded-full hover:bg-[#3f3b2e] transition">
        Login
      </a>
    </div>
  </nav>

  <!-- Banner -->
  <section class="mt-2 mx-6">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full h-[27rem]">
      <img src="./images/ban1.jpg" alt="">
    </div>
  </section>

  <!-- Categories -->
  <section class="mt-8 mx-6">
    <h2 class="text-xl font-bold mb-4">Categories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      <div class="bg-white rounded-2xl shadow-lg p-4 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1040/1040221.png" class="w-16 mx-auto mb-2" alt="Electronics">
        <p class="text-sm font-semibold">Electronics</p>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-4 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1995/1995527.png" class="w-16 mx-auto mb-2" alt="Fashion">
        <p class="text-sm font-semibold">Fashion</p>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-4 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/883/883407.png" class="w-16 mx-auto mb-2" alt="Home">
        <p class="text-sm font-semibold">Home</p>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-4 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/3039/3039414.png" class="w-16 mx-auto mb-2" alt="Beauty">
        <p class="text-sm font-semibold">Beauty</p>
      </div>
    </div>
  </section>

  <!-- Top Stores -->
  <section class="mt-8 mx-6">
    <h2 class="text-xl font-bold mb-4">Top Stores</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/1674/1674291.png" class="w-20 mx-auto mb-4" alt="Vendor 1">
        <h3 class="font-semibold">TechWorld</h3>
        <p class="text-sm text-secondary">Best gadgets & electronics</p>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" class="w-20 mx-auto mb-4" alt="Vendor 2">
        <h3 class="font-semibold">FashionFreak</h3>
        <p class="text-sm text-secondary">Trendy clothes & shoes</p>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" class="w-20 mx-auto mb-4" alt="Vendor 3">
        <h3 class="font-semibold">HomePlus</h3>
        <p class="text-sm text-secondary">Furniture & kitchen</p>
      </div>
    </div>
  </section>

  <!-- All Products -->
  <section class="mt-8 mx-6 mb-10">
    <h2 class="text-xl font-bold mb-4">Featured P