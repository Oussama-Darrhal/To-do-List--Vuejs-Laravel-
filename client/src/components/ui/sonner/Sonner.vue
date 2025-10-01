<script setup>
import { Toaster as Sonner } from "vue-sonner";

const props = defineProps({
  id: { type: String, required: false },
  invert: { type: Boolean, required: false },
  theme: { type: String, required: false },
  position: { type: String, required: false },
  hotkey: { type: Array, required: false },
  richColors: { type: Boolean, required: false },
  expand: { type: Boolean, required: false },
  duration: { type: Number, required: false },
  gap: { type: Number, required: false },
  visibleToasts: { type: Number, required: false },
  closeButton: { type: Boolean, required: false },
  toastOptions: { type: Object, required: false },
  class: { type: String, required: false },
  style: { type: Object, required: false },
  offset: { type: [Object, String, Number], required: false },
  mobileOffset: { type: [Object, String, Number], required: false },
  dir: { type: String, required: false },
  swipeDirections: { type: Array, required: false },
  icons: { type: Object, required: false },
  containerAriaLabel: { type: String, required: false },
});
</script>

<template>
  <Sonner
    class="toaster group"
    v-bind="props"
    :toast-options="{
      classes: {
        toast:
          'group toast relative group-[.toaster]:bg-card/90 backdrop-blur-md group-[.toaster]:text-card-foreground group-[.toaster]:border-border/60 group-[.toaster]:shadow-md ring-1 ring-border/60 rounded-lg px-3 py-2 text-sm transition-all duration-300 ease-out hover:shadow-lg',
        description: 'group-[.toast]:text-muted-foreground/90 text-xs',
        actionButton:
          'group-[.toast]:bg-primary group-[.toast]:text-primary-foreground group-[.toast]:hover:bg-primary/90 rounded-md',
        cancelButton:
          'group-[.toast]:bg-muted group-[.toast]:text-muted-foreground group-[.toast]:hover:bg-muted/80 rounded-md',
      },
    }"
  />
</template>

<style>
/* Compact progress bar at the top of each toast */
.toaster .toast::before {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  height: 3px;
  width: 100%;
  background: hsl(217 91% 60%); /* default info blue */
  transform-origin: left center;
  animation-name: toast-progress-deplete;
  animation-duration: var(--toast-duration, 3500ms);
  animation-timing-function: linear;
  animation-fill-mode: forwards;
}

/* Semantic colors via variant classes added at emit time */
.toaster .toast.is-success::before { background: hsl(142 71% 45%); }
.toaster .toast.is-error::before { background: hsl(0 84% 60%); }
.toaster .toast.is-warning::before { background: hsl(38 92% 50%); }
.toaster .toast.is-info::before { background: hsl(217 91% 60%); }

@keyframes toast-progress-deplete {
  from { width: 100%; }
  to { width: 0%; }
}
</style>
