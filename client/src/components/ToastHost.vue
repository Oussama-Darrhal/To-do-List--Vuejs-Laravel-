<template>
  <Toaster
  />
</template>

<script setup>
import { onMounted, watch } from "vue";
import { useNotificationsStore } from "../stores/notifications";
import { Toaster } from "@/components/ui/sonner";
import { toast } from "vue-sonner";

const store = useNotificationsStore();
const shown = new Set();

onMounted(() => {
  store.toasts.forEach((t) => {
    if (!shown.has(t.id)) {
      shown.add(t.id);
      show(t);
    }
  });
});

watch(
  () => store.toasts.slice(),
  (list) => {
    list.forEach((t) => {
      if (!shown.has(t.id)) {
        shown.add(t.id);
        show(t);
      }
    });
  }
);

function show(t) {
  const common = {
    description: t.description,
    class: undefined,
    // ensure the CSS progress uses the same duration
    style: { "--toast-duration": `${t.duration || 3500}ms` },
  };
  if (t.variant === "error" || t.variant === "destructive") {
    return toast.error(t.title, { ...common, class: "is-error" });
  }
  if (t.variant === "success") {
    return toast.success(t.title, { ...common, class: "is-success" });
  }
  if (t.variant === "warning") {
    return (
      toast.warning?.(t.title, { ...common, class: "is-warning" }) ||
      toast(t.title, { ...common, class: "is-warning" })
    );
  }
  // default info
  return toast(t.title, { ...common, class: "is-info" });
}
</script>

<style scoped></style>
