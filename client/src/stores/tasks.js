import { defineStore } from 'pinia';
import api from '../api';

export const useTasksStore = defineStore('tasks', {
  state: () => ({
    items: [],
    pagination: null,
    loading: false,
    error: null,
  }),
  actions: {
    async fetchAll(page = 1) {
      this.loading = true; this.error = null;
      try {
        const { data } = await api.get('/tasks', { params: { page } });
        // When using LengthAwarePaginator
        this.items = data.data ?? data;
        this.pagination = data.data ? { ...data } : null;
      } catch (err) {
        this.error = err.response?.data || err.message;
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async getOne(id) {
      const { data } = await api.get(`/tasks/${id}`);
      return data;
    },
    async create(payload) {
      const { data } = await api.post('/tasks', payload);
      this.items.unshift(data);
      return data;
    },
    async update(id, payload) {
      const { data } = await api.put(`/tasks/${id}`, payload);
      const idx = this.items.findIndex(t => t.id === id);
      if (idx >= 0) this.items[idx] = data;
      return data;
    },
    async remove(id) {
      await api.delete(`/tasks/${id}`);
      this.items = this.items.filter(t => t.id !== id);
    },
  },
});
