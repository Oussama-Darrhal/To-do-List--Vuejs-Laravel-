import { defineStore } from 'pinia';
import api from '../api';

let nextId = 1;

export const useNotificationsStore = defineStore('notifications', {
  state: () => ({
    toasts: [],
    messages: [],
  }),
  actions: {
    async fetchAll() {
      const { data } = await api.get('/notifications');
      // support paginator or array
      const list = data.data ?? data;
      this.messages = list;
      return list;
    },
    pushToast({ title, description, variant = 'success', duration = 3000 }) {
      const id = nextId++;
      this.toasts.push({ id, title, description, variant });
      if (duration > 0) {
        setTimeout(() => this.removeToast(id), duration);
      }
    },
    removeToast(id) {
      this.toasts = this.toasts.filter(t => t.id !== id);
    },
    addMessage(message) {
      this.messages.unshift({ ...message, at: new Date().toISOString() });
    },
    clearMessages() {
      this.messages = [];
    }
  }
});


