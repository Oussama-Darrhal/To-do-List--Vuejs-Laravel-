<template>
  <div class="mx-auto max-w-md">
    <!-- Login card -->
    <Card class="shadow-sm">
      <CardHeader>
        <CardTitle>Welcome back</CardTitle>
        <CardDescription>Enter your credentials to continue</CardDescription>
      </CardHeader>

      <CardContent>
        <!-- Login form -->
        <form class="grid gap-4" @submit.prevent="onSubmit">
          <!-- Email input -->
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              v-model="email"
              type="email"
              placeholder="you@example.com"
              required
            />
          </div>

          <!-- Password input -->
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input
              id="password"
              v-model="password"
              type="password"
              placeholder="••••••••"
              required
            />
          </div>

          <!-- Submit button -->
          <Button :disabled="auth.loading" type="submit" class="w-full">
            Login
          </Button>
        </form>

        <!-- Display authentication errors -->
        <Alert v-if="auth.error" variant="destructive" class="mt-4">
          <AlertTitle>Error</AlertTitle>
          <AlertDescription>
            {{ auth.error.message || auth.error }}
          </AlertDescription>
        </Alert>
      </CardContent>

      <!-- Footer with registration link -->
      <CardFooter>
        <p class="text-sm text-muted-foreground">
          No account?
          <RouterLink class="underline" to="/register">Register</RouterLink>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
// Vue composition API
import { ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'

// Stores
import { useAuthStore } from '@/stores/auth'

// UI components
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle
} from '@/components/ui/card'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'

// Stores and router
const auth = useAuthStore()
const router = useRouter()

// Form state
const email = ref('')
const password = ref('')

/**
 * Handles login form submission
 * Authenticates user and redirects to tasks page
 */
const onSubmit = async () => {
  await auth.login({ email: email.value, password: password.value })
  router.push({ name: 'tasks' })
}
</script>

<style scoped>
/* Scoped styles for Login component (currently empty) */
</style>
