<script setup>
// Import main layout components
import Navbar from "./components/Navbar.vue";
import ToastHost from "./components/ToastHost.vue";
</script>

<template>
  <!-- Root container for the app layout -->
  <div class="min-h-screen flex flex-col w-full">
    <!-- Global navigation bar -->
    <Navbar />

    <!-- Main content area where views will be rendered -->
    <main class="flex-1 w-full">
      <div class="w-full px-4 sm:px-6 lg:px-10 py-6 md:py-8">
        <router-view />
      </div>
    </main>

    <!-- Application footer -->
    <footer class="border-t w-full">
      <div
        class="w-full px-4 sm:px-6 lg:px-10 py-6 text-xs text-muted-foreground flex items-center justify-between"
      >
        <span>© {{ new Date().getFullYear() }} Todo App</span>
        <span class="sm:inline">
          Built with Love By
          <a
            href="https://www.linkedin.com/in/oussama-darrhal-6344ba250/"
            target="_blank"
          >
            Oussama DARRHAL
          </a>
        </span>
      </div>
    </footer>

    <!-- Global toast notification host -->
    <ToastHost
      :rich-colors="true"
      :close-button="false"
      :duration="3500"
      class="absolute bottom-[60px] right-4 max-w-xl text-base shadow-lg [&_[data-title]]:text-left [&_[data-title]]:text-lg [&_[data-title]]:font-semibold [&_[data-description]]:text-left [&_[data-description]]:text-base"
      :expand="false"
      :visible-toasts="4"
    />
  </div>
</template>

<style scoped>
/* Root-level styles specific to App.vue */
</style>
