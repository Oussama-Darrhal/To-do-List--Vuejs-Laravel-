<template>
  <div class="toast-host">
    <div v-for="t in store.toasts" :key="t.id" class="toast" :class="t.variant">
      <strong>{{ t.title }}</strong>
      <p v-if="t.description">{{ t.description }}</p>
      <button @click="store.removeToast(t.id)">×</button>
    </div>
  </div>
  </template>

<script setup>
import { useNotificationsStore } from '../stores/notifications'
const store = useNotificationsStore()
</script>

<style scoped>
.toast-host {
  position: fixed; inset: 0; pointer-events: none;
}
.toast {
  position: absolute; right: 1rem; top: 1rem; min-width: 260px;
  background: #111827; color: #fff; border-radius: 10px; padding: .75rem 1rem;
  box-shadow: 0 10px 30px rgba(0,0,0,.2); display: grid; gap: .25rem;
  pointer-events: auto;
}
.toast.success { background: #065f46; }
.toast.error { background: #7f1d1d; }
.toast button { background: transparent; border: none; color: #fff; cursor: pointer; position: absolute; right: .5rem; top: .25rem; font-size: 1.1rem; }
</style>


