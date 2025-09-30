import { defineStore } from 'pinia';
import api from '../api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('access_token') || null,
    loading: false,
    error: null,
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token),
  },
  actions: {
    async register(payload) {
      this.loading = true; this.error = null;
      try {
        const config = payload instanceof FormData ? { headers: { 'Content-Type': 'multipart/form-data' } } : undefined;
        const { data } = await api.post('/auth/register', payload, config);
        this.token = data.access_token;
        this.user = data.user;
        localStorage.setItem('access_token', this.token);
      } catch (err) {
        this.error = err.response?.data || err.message;
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async login(payload) {
      this.loading = true; this.error = null;
      try {
        const { data } = await api.post('/auth/login', payload);
        this.token = data.access_token;
        this.user = data.user;
        localStorage.setItem('access_token', this.token);
      } catch (err) {
        this.error = err.response?.data || err.message;
        throw err;
      } finally {
        this.loading = false;
      }
    },
    async fetchMe() {
      if (!this.token) return;
      const { data } = await api.get('/auth/me');
      this.user = data;
    },
    async logout() {
      try { await api.post('/auth/logout'); } catch {}
      this.token = null; this.user = null;
      localStorage.removeItem('access_token');
    },
  },
});


