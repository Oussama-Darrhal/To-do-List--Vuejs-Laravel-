<template>
  <div class="grid gap-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold tracking-tight">My Tasks</h1>
      <div class="flex items-center gap-2">
        <RouterLink to="/notifications">
          <Button variant="secondary">Notifications</Button>
        </RouterLink>
        <Button variant="outline" @click="logout">Logout</Button>
      </div>
    </div>

    <!-- Floating Create Button -->
    <Button class="fixed bottom-6 right-6 h-12 w-12 rounded-full shadow-lg" @click="openCreate">
      <Plus class="h-5 w-5" />
    </Button>

    <div class="grid gap-3">
      <Card v-if="tasks.items.length === 0" class="border-dashed">
        <CardContent class="py-12 text-center text-sm text-muted-foreground">
          No tasks yet. Create your first task above.
        </CardContent>
      </Card>
      <Card v-for="task in tasks.items" :key="task.id" :class="task.is_completed ? 'bg-green-50 dark:bg-green-950/30 border-green-200 ring-1 ring-green-300' : ''" class="transition-colors">
        <CardContent class="pt-6 relative">
          <div class="grid gap-2 md:grid-cols-[1fr_auto] md:items-start">
            <div class="grid gap-1">
              <div class="flex items-center gap-2">
                <Button size="sm" :variant="task.is_completed ? 'secondary' : 'outline'" class="h-7" @click="toggle(task)">
                  <CheckCircle2 class="h-4 w-4" :class="task.is_completed ? 'text-green-600' : ''" />
                </Button>
                <span :class="{'line-through text-muted-foreground': task.is_completed}" class="font-medium">{{ task.title }}</span>
                <Badge v-if="task.is_completed" variant="secondary" class="ml-2">Done</Badge>
                <Badge v-else-if="task.due_at && new Date(task.due_at) < new Date()" variant="destructive" class="ml-2">Overdue</Badge>
                <Badge v-else-if="task.due_at" variant="outline" class="ml-2">Due {{ new Date(task.due_at).toLocaleDateString() }}</Badge>
              </div>
              <p v-if="task.description" class="text-sm text-muted-foreground">{{ task.description }}</p>
            </div>
            <div class="flex gap-2 justify-end">
              <Button size="sm" variant="outline" @click="openEdit(task)">Edit</Button>
              <Button size="sm" variant="destructive" @click="confirmDelete(task)">Delete</Button>
              <RouterLink :to="{ name: 'task-detail', params: { id: task.id } }">
                <Button size="sm" variant="secondary">View</Button>
              </RouterLink>
            </div>
          </div>
          <transition name="fade-burst">
            <div v-if="burstId === task.id" class="pointer-events-none absolute inset-0 rounded-[calc(var(--radius)+2px)] ring-2 ring-green-400/70"></div>
          </transition>
        </CardContent>
      </Card>
    </div>

    <!-- Create/Edit Dialog -->
    <Dialog :open="dialogOpen" @update:open="(v) => dialogOpen = v">
      <DialogContent class="sm:max-w-lg">
        <DialogHeader>
          <DialogTitle>{{ currentTask ? 'Edit task' : 'Create task' }}</DialogTitle>
          <DialogDescription>
            {{ currentTask ? 'Update your task details' : 'Add details for your new task' }}
          </DialogDescription>
        </DialogHeader>
        <div class="grid gap-4 py-2">
          <div class="grid gap-2">
            <Label for="d-title">Title</Label>
            <Input id="d-title" v-model="formTitle" placeholder="Task title" />
          </div>
          <div class="grid gap-2">
            <Label for="d-desc">Description</Label>
            <Input id="d-desc" v-model="formDescription" placeholder="Description" />
          </div>
          <div class="grid gap-2">
            <Label for="d-due">Due</Label>
            <Input id="d-due" v-model="formDue" type="datetime-local" />
          </div>
        </div>
        <DialogFooter>
          <Button variant="ghost" @click="closeDialog">Cancel</Button>
          <Button @click="submitDialog">{{ currentTask ? 'Save changes' : 'Create task' }}</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <AlertDialog :open="deleteOpen" @update:open="(v) => deleteOpen = v">
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Delete task?</AlertDialogTitle>
          <AlertDialogDescription>
            Are you sure you want to delete '{{ toDelete?.title || '' }}'? This action cannot be undone.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel @click="deleteOpen=false; toDelete=null">Cancel</AlertDialogCancel>
          <AlertDialogAction @click="doDelete">Delete</AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>

    
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useTasksStore } from '@/stores/tasks'
import { useNotificationsStore } from '@/stores/notifications'
import Button from '@/components/ui/button/Button.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog'
import { Toaster } from '@/components/ui/sonner'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { CheckCircle2, Plus } from 'lucide-vue-next'

const auth = useAuthStore()
const tasks = useTasksStore()
const router = useRouter()
const notify = useNotificationsStore()

const title = ref('')
const description = ref('')
const due_at = ref('')
const editId = ref(null)
const editTitle = ref('')
const editDescription = ref('')
const deleteOpen = ref(false)
const toDelete = ref(null)
const burstId = ref(null)
const dialogOpen = ref(false)
const currentTask = ref(null)
const formTitle = ref('')
const formDescription = ref('')
const formDue = ref('')

onMounted(() => {
  tasks.fetchAll()
})

const create = async () => {
  await tasks.create({ title: title.value, description: description.value || undefined, due_at: due_at.value ? new Date(due_at.value).toISOString() : undefined })
  title.value = ''; description.value=''; due_at.value=''
  notify.pushToast({ title: 'Task created', description: 'Your task was created successfully.' })
}

const toggle = async (task) => {
  await tasks.update(task.id, { is_completed: !task.is_completed })
  burstId.value = task.id
  setTimeout(() => { burstId.value = null }, 500)
}

const confirmDelete = (task) => {
  toDelete.value = task
  deleteOpen.value = true
}

const doDelete = async () => {
  if (!toDelete.value) return
  await tasks.remove(toDelete.value.id)
  deleteOpen.value = false
  notify.pushToast({ title: 'Task deleted', description: 'The task was deleted.' })
  toDelete.value = null
}

const logout = async () => {
  await auth.logout();
  router.push({ name: 'login' })
}

const startEdit = (task) => {
  editId.value = task.id
  editTitle.value = task.title
  editDescription.value = task.description || ''
}

const cancelEdit = () => {
  editId.value = null
  editTitle.value = ''
  editDescription.value = ''
}

const saveEdit = async (task) => {
  const payload = { title: editTitle.value, description: editDescription.value || null }
  await tasks.update(task.id, payload)
  cancelEdit()
  notify.pushToast({ title: 'Task updated', description: 'Changes saved.' })
}

const openCreate = () => {
  currentTask.value = null
  formTitle.value = ''
  formDescription.value = ''
  formDue.value = ''
  dialogOpen.value = true
}

const openEdit = (task) => {
  currentTask.value = task
  formTitle.value = task.title
  formDescription.value = task.description || ''
  formDue.value = task.due_at ? new Date(task.due_at).toISOString().slice(0,16) : ''
  dialogOpen.value = true
}

const closeDialog = () => {
  dialogOpen.value = false
}

const submitDialog = async () => {
  const payload = {
    title: formTitle.value,
    description: formDescription.value || undefined,
    due_at: formDue.value ? new Date(formDue.value).toISOString() : undefined,
  }
  if (currentTask.value) {
    await tasks.update(currentTask.value.id, payload)
    notify.pushToast({ title: 'Task updated', description: 'Changes saved.' })
  } else {
    await tasks.create(payload)
    notify.pushToast({ title: 'Task created', description: 'Your task was created successfully.' })
  }
  dialogOpen.value = false
}
</script>

<style scoped>
.fade-burst-enter-active, .fade-burst-leave-active { transition: opacity .5s ease; }
.fade-burst-enter-from, .fade-burst-leave-to { opacity: 0; }
</style>


