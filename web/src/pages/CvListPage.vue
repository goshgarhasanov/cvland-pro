<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { useI18n } from 'vue-i18n'

import { api } from '@/lib/api'
import type { ApiCollection, Cv } from '@/types/api'

const { t } = useI18n()

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
    <h1 class="text-3xl font-bold tracking-tight">{{ t('nav.cvs') }}</h1>

    <div v-if="isLoading" class="mt-8 text-slate-500">{{ t('common.loading') }}</div>

    <ul v-else-if="data && data.data.length > 0" class="mt-8 divide-y divide-slate-200 rounded-2xl border border-slate-200 dark:divide-slate-800 dark:border-slate-800">
      <li v-for="cv in data.data" :key="cv.id" class="flex items-center justify-between p-4">
        <div>
          <p class="font-medium">{{ cv.title }}</p>
          <p class="text-xs text-slate-500">{{ cv.status }} · {{ new Date(cv.updated_at).toLocaleDateString() }}</p>
        </div>
        <button class="btn-secondary">{{ t('common.edit') }}</button>
      </li>
    </ul>

    <p v-else class="mt-8 text-slate-500">{{ t('dashboard.empty_title') }}</p>
  </section>
</template>
