<script setup>
import Navbar from './components/Navbar.vue'
import ToastHost from './components/ToastHost.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col w-full">
    <Navbar />
    <main class="flex-1 w-full">
      <div class="w-full px-4 sm:px-6 lg:px-10 py-6 md:py-8">
        <router-view />
      </div>
    </main>
    <footer class="border-t w-full">
      <div class="w-full px-4 sm:px-6 lg:px-10 py-6 text-xs text-muted-foreground flex items-center justify-between">
        <span>© {{ new Date().getFullYear() }} Todo App</span>
        <span class="hidden sm:inline">Built with Vue 3, Pinia, shadcn-vue</span>
      </div>
    </footer>
    <ToastHost />
  </div>
</template>

<style scoped>
</style>
