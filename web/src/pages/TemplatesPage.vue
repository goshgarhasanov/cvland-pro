<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { useI18n } from 'vue-i18n'

import { api } from '@/lib/api'
import type { ApiCollection, Template } from '@/types/api'

const { t } = useI18n()

const { data, isLoading } = useQuery({
  queryKey: ['templates'],
  queryFn: async () => {
    const response = await api.get<ApiCollection<Template>>('/v1/templates')
    return response.data
  },
})
</script>

<template>
  <section class="mx-auto max-w-7xl px-6 py-12">
    <h1 class="text-3xl font-bold tracking-tight">{{ t('nav.templates') }}</h1>

    <div v-if="isLoading" class="mt-8 text-slate-500">{{ t('common.loading') }}</div>

    <div v-else-if="data" class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <article v-for="template in data.data" :key="template.id" class="card group cursor-pointer transition-all hover:-translate-y-1 hover:shadow-lg">
        <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900" />
        <div class="mt-4 flex items-start justify-between">
          <div>
            <h3 class="font-semibold">{{ template.name }}</h3>
            <p class="text-xs text-slate-500">{{ template.category }}</p>
          </div>
          <span class="text-sm font-semibold text-brand-700 dark:text-brand-300">{{ template.price.formatted }}</span>
        </div>
      </article>
    </div>
  </section>
</template>
