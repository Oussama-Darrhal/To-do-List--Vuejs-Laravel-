<template>
  <!-- Task detail view -->
  <div v-if="task" class="mx-auto max-w-2xl">
    <Card>
      <CardHeader>
        <!-- Task title -->
        <CardTitle>{{ task.title }}</CardTitle>

        <!-- Optional due date -->
        <CardDescription v-if="task.due_at">
          Due {{ new Date(task.due_at).toLocaleString() }}
        </CardDescription>
      </CardHeader>

      <CardContent class="grid gap-2">
        <!-- Optional description -->
        <p v-if="task.description" class="text-sm text-muted-foreground">
          {{ task.description }}
        </p>

        <!-- Task status -->
        <div class="text-sm">
          <span class="font-medium">Status:</span>
          <span
            class="ml-2"
            :class="task.is_completed ? 'text-green-600 dark:text-green-500' : 'text-yellow-600 dark:text-yellow-500'"
          >
            {{ task.is_completed ? 'Completed' : 'Open' }}
          </span>
        </div>
      </CardContent>

      <!-- Navigation back to task list -->
      <CardFooter>
        <RouterLink to="/tasks">
          <Button variant="secondary">Back to tasks</Button>
        </RouterLink>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
// Vue & router imports
import { ref, onMounted } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'

// Store for tasks
import { useTasksStore } from '@/stores/tasks'

// UI components
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import Button from '@/components/ui/button/Button.vue'

// Local state
const route = useRoute()
const router = useRouter()
const tasks = useTasksStore()
const task = ref(null)

// Fetch task details when component mounts
onMounted(async () => {
  try {
    task.value = await tasks.getOne(Number(route.params.id))
  } catch (e) {
    // Redirect back to list if task not found
    router.replace({ name: 'tasks' })
  }
})
</script>

<style scoped>
/* Scoped styles for task detail view */
</style>
