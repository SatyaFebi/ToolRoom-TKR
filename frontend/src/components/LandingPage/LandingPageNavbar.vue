<!-- src/components/LandingPage/LandingPageNavbar.vue -->
<template>
  <nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm shadow-lg z-50 border-b border-gray-100">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <img src="/assets/img/logo.png" alt="logo" class="w-12 h-12 rounded-xl object-cover" />
        <span class="font-bold text-xl text-[#12385f]">Toolroom TKR</span>
      </div>

      <div>
        <router-link
          to="/login"
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#12385f] hover:bg-[#0d2844] text-white font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          Login Admin
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup></script>