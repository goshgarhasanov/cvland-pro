<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { LayoutTemplate, Sparkles } from 'lucide-vue-next'
import { ref } from 'vue'
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

const filter = ref<'all' | 'free' | 'premium'>('all')

const accents = [
  'from-brand-500/20 via-fuchsia-500/10 to-transparent',
  'from-emerald-500/20 via-teal-500/10 to-transparent',
  'from-amber-500/20 via-rose-500/10 to-transparent',
  'from-sky-500/20 via-blue-500/10 to-transparent',
  'from-pink-500/20 via-purple-500/10 to-transparent',
  'from-slate-300/10 via-slate-500/5 to-transparent',
]

function accentFor(idx: number) {
  return accents[idx % accents.length]
}
</script>

<template>
  <section class="mx-auto max-w-7xl px-6 py-12">
    <header>
      <h1 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ t('templates.title') }}</h1>
      <p class="mt-2 max-w-2xl text-slate-400">{{ t('templates.subtitle') }}</p>
    </header>

    <!-- Filter chips -->
    <div class="mt-8 inline-flex rounded-xl border border-slate-800 bg-slate-900/60 p-1">
      <button
        v-for="opt in (['all', 'free', 'premium'] as const)"
        :key="opt"
        type="button"
        class="rounded-lg px-4 py-1.5 text-sm font-medium transition-colors"
        :class="filter === opt
          ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30'
          : 'text-slate-400 hover:text-white'"
        @click="filter = opt"
      >
        {{ t(`templates.filter_${opt}`) }}
      </button>
    </div>

    <div v-if="isLoading" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <div v-for="i in 6" :key="i" class="card animate-pulse">
        <div class="aspect-[3/4] rounded-xl bg-slate-800/60"></div>
        <div class="mt-4 h-3 w-24 rounded bg-slate-800"></div>
      </div>
    </div>

    <div v-else-if="data && data.data.length > 0" class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <article
        v-for="(template, idx) in data.data"
        :key="template.id"
        class="card card-hover group cursor-pointer overflow-hidden p-0"
      >
        <div
          class="relative aspect-[3/4] overflow-hidden bg-gradient-to-br"
          :class="accentFor(idx)"
        >
          <!-- Premium badge -->
          <span
            v-if="template.price.minor > 0"
            class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full border border-amber-500/40 bg-amber-500/15 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-amber-300 backdrop-blur"
          >
            <Sparkles class="h-3 w-3" />
            Premium
          </span>

          <!-- Mock CV preview -->
          <div class="absolute inset-6 rounded-xl border border-slate-700/60 bg-slate-950/70 p-5 backdrop-blur transition-transform duration-500 group-hover:scale-105">
            <div class="flex items-center gap-2">
              <div class="h-8 w-8 rounded-full bg-gradient-to-br from-brand-400 to-fuchsia-400"></div>
              <div class="space-y-1.5">
                <div class="h-2 w-24 rounded-full bg-slate-700"></div>
                <div class="h-1.5 w-16 rounded-full bg-slate-800"></div>
              </div>
            </div>
            <div class="my-4 h-px bg-slate-800"></div>
            <div class="space-y-1.5">
              <div class="h-1.5 w-20 rounded-full bg-brand-400/60"></div>
              <div class="h-1 w-full rounded-full bg-slate-800"></div>
              <div class="h-1 w-5/6 rounded-full bg-slate-800"></div>
              <div class="h-1 w-4/6 rounded-full bg-slate-800"></div>
            </div>
            <div class="mt-4 space-y-1.5">
              <div class="h-1.5 w-16 rounded-full bg-brand-400/60"></div>
              <div class="h-1 w-full rounded-full bg-slate-800"></div>
              <div class="h-1 w-3/4 rounded-full bg-slate-800"></div>
            </div>
          </div>

          <!-- Hover overlay -->
          <div class="absolute inset-0 flex items-end justify-center bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-5 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <button class="btn-primary">
              <LayoutTemplate class="h-4 w-4" />
              {{ t('templates.use') }}
            </button>
          </div>
        </div>
        <div class="flex items-start justify-between p-5">
          <div>
            <h3 class="font-semibold text-white">{{ template.name }}</h3>
            <p class="mt-0.5 text-xs text-slate-400">{{ template.category }}</p>
          </div>
          <span
            class="text-sm font-semibold"
            :class="template.price.minor > 0 ? 'text-amber-300' : 'text-emerald-300'"
          >
            {{ template.price.minor > 0 ? template.price.formatted : 'Pulsuz' }}
          </span>
        </div>
      </article>
    </div>

    <div v-else class="card mt-10 flex flex-col items-center justify-center py-16 text-center">
      <div class="grid h-14 w-14 place-items-center rounded-2xl bg-brand-500/10 text-brand-300">
        <LayoutTemplate class="h-7 w-7" />
      </div>
      <h3 class="mt-5 text-lg font-semibold text-white">{{ t('common.loading') }}</h3>
    </div>
  </section>
</template>
