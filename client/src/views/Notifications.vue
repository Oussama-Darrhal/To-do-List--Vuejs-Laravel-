<template>
  <div class="container">
    <h1>Notifications</h1>
    <p v-if="!messages.length">No notifications yet.</p>
    <ul>
      <li v-for="(m, i) in messages" :key="i">
        New task created: <strong>{{ m.title }}</strong>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { useAuthStore } from '../stores/auth'

const messages = ref([])
const auth = useAuthStore()
let echo

onMounted(async () => {
  if (!auth.user) {
    try { await auth.fetchMe() } catch {}
  }
  const token = localStorage.getItem('access_token')

  window.Pusher = Pusher
  echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
    wsPort: Number(import.meta.env.VITE_PUSHER_PORT || 6001),
    wssPort: Number(import.meta.env.VITE_PUSHER_PORT || 6001),
    forceTLS: Boolean(import.meta.env.VITE_PUSHER_TLS || false),
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel) => ({
      authorize: (socketId, callback) => {
        fetch((import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api').replace('/api','') + '/broadcasting/auth', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Socket-Id': socketId,
            'Authorization': `Bearer ${token}`,
          },
          body: JSON.stringify({ channel_name: channel.name }),
        })
          .then(res => res.json())
          .then(data => callback(false, data))
          .catch(err => callback(true, err))
      }
    })
  })

  const channelName = `private-users.${auth.user?.id}`
  echo.private(channelName).listen('.task.created', (e) => {
    messages.value.unshift(e)
  })
})

onBeforeUnmount(() => {
  if (echo) {
    echo.disconnect()
  }
})
</script>

<style scoped>
.container { max-width: 720px; margin: 2rem auto; }
ul { list-style: none; padding: 0; display: grid; gap: .5rem; }
li { padding: .5rem .75rem; border: 1px solid #eee; border-radius: 8px; }
</style>


