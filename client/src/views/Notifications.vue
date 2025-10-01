<template>
  <div class="grid gap-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold tracking-tight">Notifications</h1>
    </div>

    <Card>
      <CardHeader>
        <CardTitle>All notifications</CardTitle>
        <CardDescription>Includes stored and live session items</CardDescription>
      </CardHeader>
      <CardContent>
        <template v-if="notify.messages.length">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Type</TableHead>
                <TableHead>Title</TableHead>
                <TableHead>Message</TableHead>
                <TableHead>Created</TableHead>
                <TableHead>Status</TableHead>
                <TableHead class="text-right">Action</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="(m, i) in notify.messages" :key="`store-${i}`">
                <TableCell class="font-medium">{{ m.type }}</TableCell>
                <TableCell>{{ m.title }}</TableCell>
                <TableCell class="text-muted-foreground">{{ m.message || m.description || '' }}</TableCell>
                <TableCell>{{ new Date(m.created_at || m.at).toLocaleString() }}</TableCell>
                <TableCell>
                  <Badge :variant="m.is_completed ? 'secondary' : 'outline'">{{ m.is_completed ? 'Completed' : 'Open' }}</Badge>
                </TableCell>
                <TableCell class="text-right">
                  <RouterLink v-if="m.task_id" :to="{ name: 'task-detail', params: { id: m.task_id } }">
                    <Button size="sm" variant="secondary">View</Button>
                  </RouterLink>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </template>
        <p v-else class="text-sm text-muted-foreground">No notifications yet.</p>
      </CardContent>
    </Card>

    <Card v-if="messages.length">
      <CardHeader>
        <CardTitle>Live session</CardTitle>
        <CardDescription>Real-time updates during your session</CardDescription>
      </CardHeader>
      <CardContent>
        <ul class="grid gap-2">
          <li v-for="(m, i) in messages" :key="`live-${i}`" class="text-sm">
            New task created: <span class="font-medium">{{ m.title }}</span>
          </li>
        </ul>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { useAuthStore } from '@/stores/auth'
import { useNotificationsStore } from '@/stores/notifications'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import Button from '@/components/ui/button/Button.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import { RouterLink } from 'vue-router'

const messages = ref([])
const auth = useAuthStore()
const notify = useNotificationsStore()
let echo

onMounted(async () => {
  if (!auth.user) {
    try { await auth.fetchMe() } catch {}
  }
  await notify.fetchAll()
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
    notify.addMessage({ type: 'task.created', ...e })
  })
})

onBeforeUnmount(() => {
  if (echo) {
    echo.disconnect()
  }
})
</script>

<style scoped>
</style>


