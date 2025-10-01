<template>
  <div class="mx-auto max-w-md">
    <!-- Registration form card -->
    <Card class="shadow-sm">
      <CardHeader>
        <CardTitle>Create an account</CardTitle>
        <CardDescription>Fill in the details to get started</CardDescription>
      </CardHeader>

      <CardContent>
        <!-- Registration form -->
        <form class="grid gap-4" @submit.prevent="onSubmit" enctype="multipart/form-data">
          <!-- Full name -->
          <div class="grid gap-2">
            <Label for="full_name">Full name</Label>
            <Input
              id="full_name"
              v-model="full_name"
              placeholder="Jane Doe"
              required
            />
          </div>

          <!-- Email -->
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

          <!-- Phone -->
          <div class="grid gap-2">
            <Label for="phone">Phone number</Label>
            <Input
              id="phone"
              v-model="phone_number"
              type="tel"
              placeholder="+212 600 000 000"
            />
          </div>

          <!-- Address -->
          <div class="grid gap-2">
            <Label for="address">Address</Label>
            <Input
              id="address"
              v-model="address"
              placeholder="123 Main Street, City"
            />
          </div>

          <!-- Profile image upload -->
          <div class="grid gap-2">
            <Label for="image">Profile image</Label>
            <Input
              id="image"
              type="file"
              @change="onFile"
              accept="image/*"
            />
          </div>

          <!-- Password -->
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

          <!-- Confirm password -->
          <div class="grid gap-2">
            <Label for="confirm_password">Confirm Password</Label>
            <Input
              id="confirm_password"
              v-model="confirm_password"
              type="password"
              placeholder="••••••••"
              required
            />
            <!-- Inline validation -->
            <p v-if="passwordMismatch" class="text-red-500 text-sm">
              Passwords do not match
            </p>
          </div>

          <!-- Submit button -->
          <Button :disabled="auth.loading" type="submit" class="w-full">
            Create account
          </Button>
        </form>

        <!-- Error message -->
        <Alert v-if="auth.error" variant="destructive" class="mt-4">
          <AlertTitle>Error</AlertTitle>
          <AlertDescription>
            {{ auth.error.message || auth.error }}
          </AlertDescription>
        </Alert>
      </CardContent>

      <CardFooter>
        <!-- Navigation to login page -->
        <p class="text-sm text-muted-foreground">
          Have an account?
          <RouterLink class="underline" to="/login">Login</RouterLink>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'

// Store & router
const auth = useAuthStore()
const router = useRouter()

// Form fields
const full_name = ref('')
const email = ref('')
const phone_number = ref('')
const address = ref('')
const password = ref('')
const confirm_password = ref('')
const file = ref(null)

// Derived state for password validation
const passwordMismatch = computed(
  () => password.value && confirm_password.value && password.value !== confirm_password.value
)

// Handle file input
const onFile = (e) => {
  file.value = e.target.files?.[0] || null
}

// Handle form submission
const onSubmit = async () => {
  // Prevent submission if passwords don’t match
  if (passwordMismatch.value) {
    return
  }

  // Build multipart form data
  const fd = new FormData()
  fd.append('full_name', full_name.value)
  fd.append('email', email.value)
  if (phone_number.value) fd.append('phone_number', phone_number.value)
  if (address.value) fd.append('address', address.value)
  fd.append('password', password.value)
  if (file.value) fd.append('image', file.value)

  // Call auth store action
  await auth.register(fd)

  // Redirect to tasks page after successful registration
  router.push({ name: 'tasks' })
}
</script>

<style scoped>
/* Scoped styles for Register component (currently empty) */
</style>
