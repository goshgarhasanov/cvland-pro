import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

import { api } from '@/lib/api'
import type { ApiResource, User } from '@/types/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const initialised = ref(false)
  const loading = ref(false)

  const isAuthenticated = computed(() => user.value !== null)

  async function initialise(): Promise<void> {
    if (initialised.value) return
    try {
      const { data } = await api.get<ApiResource<User>>('/v1/auth/me')
      user.value = data.data
    } catch {
      user.value = null
    } finally {
      initialised.value = true
    }
  }

  async function register(payload: {
    name: string
    email: string
    password: string
    password_confirmation: string
  }): Promise<void> {
    loading.value = true
    try {
      const { data } = await api.post<ApiResource<User>>('/v1/auth/register', payload)
      user.value = data.data
    } finally {
      loading.value = false
    }
  }

  async function login(credentials: {
    email: string
    password: string
    remember?: boolean
  }): Promise<void> {
    loading.value = true
    try {
      const { data } = await api.post<ApiResource<User>>('/v1/auth/login', credentials)
      user.value = data.data
    } finally {
      loading.value = false
    }
  }

  async function logout(): Promise<void> {
    try {
      await api.post('/v1/auth/logout')
    } finally {
      user.value = null
    }
  }

  return {
    user,
    initialised,
    loading,
    isAuthenticated,
    initialise,
    register,
    login,
    logout,
  }
})
