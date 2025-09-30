<template>
  <div v-if="open" class="overlay">
    <div class="dialog">
      <h3>{{ title }}</h3>
      <p v-if="message">{{ message }}</p>
      <div class="actions">
        <button @click="$emit('cancel')">Cancel</button>
        <button class="danger" @click="$emit('confirm')">Confirm</button>
      </div>
    </div>
  </div>
  </template>

<script setup>
defineProps({ open: Boolean, title: String, message: String })
defineEmits(['confirm', 'cancel'])
</script>

<style scoped>
.overlay { position: fixed; inset: 0; background: rgba(0,0,0,.4); display: grid; place-items: center; }
.dialog { background: #fff; padding: 1rem; border-radius: 10px; width: 360px; display: grid; gap: .75rem; }
.actions { display: flex; justify-content: flex-end; gap: .5rem; }
button { padding: .5rem .75rem; border: none; border-radius: 8px; cursor: pointer; }
.danger { background: #7f1d1d; color: #fff; }
</style>


