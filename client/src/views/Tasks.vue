<template>
  <div class="container">
    <header class="header">
      <h1>My Tasks</h1>
      <div class="actions">
        <router-link to="/notifications">Notifications</router-link>
        <button @click="logout">Logout</button>
      </div>
    </header>

    <form class="new-task" @submit.prevent="create">
      <input v-model="title" placeholder="Task title" required />
      <input v-model="description" placeholder="Description" />
      <input v-model="due_at" type="datetime-local" />
      <button type="submit">Add</button>
    </form>

    <ul class="list">
      <li v-for="task in tasks.items" :key="task.id" class="item">
        <label>
          <input type="checkbox" :checked="task.is_completed" @change="toggle(task)" />
          <span :class="{done: task.is_completed}">{{ task.title }}</span>
        </label>
        <small>{{ task.description }}</small>
        <button @click="remove(task)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useTasksStore } from '../stores/tasks'

const auth = useAuthStore()
const tasks = useTasksStore()
const router = useRouter()

const title = ref('')
const description = ref('')
const due_at = ref('')

onMounted(() => {
  tasks.fetchAll()
})

const create = async () => {
  await tasks.create({ title: title.value, description: description.value || undefined, due_at: due_at.value ? new Date(due_at.value).toISOString() : undefined })
  title.value = ''; description.value=''; due_at.value=''
}

const toggle = async (task) => {
  await tasks.update(task.id, { is_completed: !task.is_completed })
}

const remove = async (task) => {
  await tasks.remove(task.id)
}

const logout = async () => {
  await auth.logout();
  router.push({ name: 'login' })
}
</script>

<style scoped>
.container { max-width: 720px; margin: 2rem auto; }
.header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.actions { display: flex; gap: .5rem; }
.new-task { display: grid; grid-template-columns: 2fr 3fr 2fr auto; gap: .5rem; margin-bottom: 1rem; }
.list { list-style: none; padding: 0; display: grid; gap: .5rem; }
.item { display: grid; grid-template-columns: 1fr auto; gap: .5rem; padding: .75rem; border: 1px solid #eee; border-radius: 8px; }
.item label { display: grid; gap: .25rem; }
.item small { color: #6b7280; }
.item .done { text-decoration: line-through; color: #6b7280; }
button { padding: .5rem .75rem; border: none; background: #1f2937; color: #fff; border-radius: 8px; cursor: pointer; }
input { padding: .5rem .6rem; border: 1px solid #ddd; border-radius: 8px; }
</style>


