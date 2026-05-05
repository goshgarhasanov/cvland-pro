<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

import { api } from '@/lib/api'
import { useAuthStore } from '@/stores/auth'
import type { ApiCollection, Cv } from '@/types/api'

const { t } = useI18n()
const auth = useAuthStore()

const { data, isLoading } = useQuery({
  queryKey: ['cvs'],
  queryFn: async () => {
    const response = await api.get<ApiCollection<Cv>>('/v1/cvs')
    return response.data
  },
})
</script>

<template>
  <section class="mx-auto max-w-7xl px-6 py-12">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold tracking-tight">{{ t('dashboard.title') }}</h1>
        <p v-if="auth.user" class="mt-1 text-slate-500">{{ t('dashboard.welcome', { name: auth.user.name }) }}</p>
      </div>
      <RouterLink :to="{ name: 'cvs.index' }" class="btn-primary">{{ t('dashboard.create_new') }}</RouterLink>
    </header>

    <div class="mt-10">
      <h2 class="text-lg font-semibold">{{ t('dashboard.your_cvs') }}</h2>

      <div v-if="isLoading" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="i in 3" :key="i" class="card animate-pulse">
          <div class="h-3 w-24 rounded bg-slate-200 dark:bg-slate-700" />
          <div class="mt-4 h-2 w-full rounded bg-slate-200 dark:bg-slate-700" />
          <div class="mt-2 h-2 w-2/3 rounded bg-slate-200 dark:bg-slate-700" />
        </div>
      </div>

      <div v-else-if="data && data.data.length > 0" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <article v-for="cv in data.data" :key="cv.id" class="card transition-shadow hover:shadow-md">
          <div class="flex items-start justify-between">
            <h3 class="font-semibold">{{ cv.title }}</h3>
            <span class="rounded-full bg-brand-100 px-2 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-900 dark:text-brand-300">
              {{ cv.status }}
            </span>
          </div>
          <p class="mt-2 text-xs text-slate-500">Updated {{ new Date(cv.updated_at).toLocaleDateString() }}</p>
        </article>
      </div>

      <div v-else class="card mt-4 text-center">
        <h3 class="font-semibold">{{ t('dashboard.empty_title') }}</h3>
        <p class="mt-1 text-sm text-slate-500">{{ t('dashboard.empty_subtitle') }}</p>
      </div>
    </div>
  </section>
</template>
