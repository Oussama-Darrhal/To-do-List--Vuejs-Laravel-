<template>
  <div v-if="task" class="mx-auto max-w-2xl">
    <Card>
      <CardHeader>
        <CardTitle>{{ task.title }}</CardTitle>
        <CardDescription v-if="task.due_at">Due {{ new Date(task.due_at).toLocaleString() }}</CardDescription>
      </CardHeader>
      <CardContent class="grid gap-2">
        <p v-if="task.description" class="text-sm text-muted-foreground">{{ task.description }}</p>
        <div class="text-sm">
          <span class="font-medium">Status:</span>
          <span class="ml-2" :class="task.is_completed ? 'text-green-600 dark:text-green-500' : 'text-yellow-600 dark:text-yellow-500'">
            {{ task.is_completed ? 'Completed' : 'Open' }}
          </span>
        </div>
      </CardContent>
      <CardFooter>
        <RouterLink to="/tasks">
          <Button variant="secondary">Back to tasks</Button>
        </RouterLink>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useTasksStore } from '@/stores/tasks'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import Button from '@/components/ui/button/Button.vue'

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
</style>


