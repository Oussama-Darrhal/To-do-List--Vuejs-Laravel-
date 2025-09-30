<template>
  <div class="container" v-if="task">
    <h1>{{ task.title }}</h1>
    <p>{{ task.description }}</p>
    <p><strong>Status:</strong> {{ task.is_completed ? 'Completed' : 'Open' }}</p>
    <p v-if="task.due_at"><strong>Due:</strong> {{ new Date(task.due_at).toLocaleString() }}</p>
    <router-link to="/tasks">Back to tasks</router-link>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTasksStore } from '../stores/tasks'

const route = useRoute()
const router = useRouter()
const tasks = useTasksStore()
const task = ref(null)

onMounted(async () => {
  try {
    task.value = await tasks.getOne(Number(route.params.id))
  } catch (e) {
    router.replace({ name: 'tasks' })
  }
})
</script>

<style scoped>
.container { max-width: 720px; margin: 2rem auto; display: grid; gap: .5rem; }
</style>


