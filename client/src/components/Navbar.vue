<template>
  <div class="sticky top-0 z-50 border-b bg-background/60 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="w-full px-4 sm:px-6 lg:px-10 h-14 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <RouterLink to="/tasks" class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-primary">
            <path fill-rule="evenodd" d="M7.5 3A2.5 2.5 0 005 5.5v13A2.5 2.5 0 007.5 21h9a2.5 2.5 0 002.5-2.5v-13A2.5 2.5 0 0016.5 3h-9zM8 6.75a.75.75 0 000 1.5h8a.75.75 0 000-1.5H8zm0 3.5a.75.75 0 000 1.5h8a.75.75 0 000-1.5H8zm0 3.5a.75.75 0 000 1.5h5a.75.75 0 000-1.5H8z" clip-rule="evenodd" />
          </svg>
          <span class="font-semibold tracking-tight">Todo List App</span>
        </RouterLink>
        <nav v-if="auth.isAuthenticated" class="hidden md:flex items-center gap-6 text-sm text-muted-foreground">
          <RouterLink to="/tasks" class="hover:text-foreground">Tasks</RouterLink>
          <RouterLink to="/notifications" class="hover:text-foreground">Notifications</RouterLink>
        </nav>
      </div>

      <div class="flex items-center gap-2">
        <Button variant="ghost" class="h-8 w-8 p-0" @click="toggleTheme" :aria-label="`Switch to ${theme.isDark ? 'light' : 'dark'} mode`">
          <Sun v-if="theme.isDark" class="h-4 w-4" />
          <Moon v-else class="h-4 w-4" />
        </Button>
        <DropdownMenu v-if="auth.isAuthenticated">
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="h-8 w-8 rounded-full p-0">
              <Avatar class="h-8 w-8">
                <AvatarImage :src="avatarUrl" alt="User" />
                <AvatarFallback>{{ initials }}</AvatarFallback>
              </Avatar>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" class="w-56">
            <DropdownMenuLabel>
              <div class="flex items-center gap-3">
                <Avatar class="h-8 w-8">
                  <AvatarImage :src="avatarUrl" alt="User" />
                  <AvatarFallback>{{ initials }}</AvatarFallback>
                </Avatar>
                <div class="grid">
                  <span class="text-sm font-medium leading-none">{{ displayName }}</span>
                  <span v-if="auth.user?.email" class="text-xs text-muted-foreground">{{ auth.user.email }}</span>
                </div>
              </div>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem as-child>
              <RouterLink to="/tasks" class="w-full">My Tasks</RouterLink>
            </DropdownMenuItem>
            <DropdownMenuItem as-child>
              <RouterLink to="/notifications" class="w-full">Notifications</RouterLink>
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem @select="onLogout">Logout</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import Button from '@/components/ui/button/Button.vue'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Moon, Sun } from 'lucide-vue-next'

const auth = useAuthStore()
const router = useRouter()
const theme = useThemeStore()

const displayName = computed(() => auth.user?.full_name || auth.user?.name || 'User')
const initials = computed(() => {
  const n = displayName.value
  const parts = String(n).trim().split(' ')
  const letters = parts.slice(0, 2).map(p => p[0] || '').join('')
  return letters || 'U'
})

const avatarUrl = computed(() => {
  // If backend serves image path on user.image, turn it into absolute; else placeholder
  const img = auth.user?.image
  if (!img) return `https://api.dicebear.com/9.x/initials/svg?seed=${encodeURIComponent(displayName.value)}`
  if (/^https?:\/\//.test(img)) return img
  const base = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api'
  return base.replace(/\/api$/, '') + '/storage/' + img.replace(/^\/?storage\//, '')
})

const onLogout = async () => {
  await auth.logout()
  router.push({ name: 'login' })
}

const toggleTheme = () => theme.toggle()
</script>

<style scoped>
</style>