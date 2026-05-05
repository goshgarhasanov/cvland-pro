<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

const { t } = useI18n()

const templates = [
  { key: 'modern', accent: 'from-brand-500/20 via-fuchsia-500/10 to-transparent', bar: 'bg-brand-400' },
  { key: 'classic', accent: 'from-amber-500/20 via-rose-500/10 to-transparent', bar: 'bg-amber-400' },
  { key: 'creative', accent: 'from-pink-500/20 via-purple-500/10 to-transparent', bar: 'bg-pink-400' },
  { key: 'minimal', accent: 'from-slate-300/10 via-slate-500/5 to-transparent', bar: 'bg-slate-300' },
  { key: 'executive', accent: 'from-emerald-500/20 via-teal-500/10 to-transparent', bar: 'bg-emerald-400' },
] as const
</script>

<template>
  <section id="templates" class="scroll-mt-24 py-24">
    <div class="mx-auto max-w-7xl px-6">
      <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-end">
        <div class="max-w-2xl">
          <span class="kicker">{{ t('home.templates.kicker') }}</span>
          <h2 class="mt-4 section-title">{{ t('home.templates.title') }}</h2>
          <p class="section-subtitle">{{ t('home.templates.subtitle') }}</p>
        </div>
        <RouterLink :to="{ name: 'templates.index' }" class="btn-link inline-flex items-center gap-1">
          {{ t('home.templates.view_all') }}
          <ArrowRight class="h-4 w-4" />
        </RouterLink>
      </div>

      <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <article
          v-for="(tpl, idx) in templates"
          :key="tpl.key"
          class="card card-hover group cursor-pointer overflow-hidden p-0"
          :class="idx === 0 ? 'lg:col-span-2' : ''"
        >
          <div
            class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br"
            :class="tpl.accent"
          >
            <!-- Mock CV inside card -->
            <div class="absolute inset-6 rounded-xl border border-slate-700/60 bg-slate-950/70 p-4 backdrop-blur transition-transform duration-500 group-hover:scale-105">
              <div class="flex items-center gap-2">
                <div class="h-6 w-6 rounded-full" :class="tpl.bar"></div>
                <div class="space-y-1">
                  <div class="h-1.5 w-20 rounded-full bg-slate-700"></div>
                  <div class="h-1 w-14 rounded-full bg-slate-800"></div>
                </div>
              </div>
              <div class="my-3 h-px bg-slate-800"></div>
              <div class="space-y-1.5">
                <div class="h-1 w-full rounded-full bg-slate-800"></div>
                <div class="h-1 w-5/6 rounded-full bg-slate-800"></div>
                <div class="h-1 w-4/6 rounded-full bg-slate-800"></div>
              </div>
              <div class="mt-3 flex gap-1">
                <div class="h-1.5 w-8 rounded-full" :class="tpl.bar"></div>
                <div class="h-1.5 w-6 rounded-full bg-slate-700"></div>
                <div class="h-1.5 w-5 rounded-full bg-slate-700"></div>
              </div>
            </div>
          </div>
          <div class="flex items-center justify-between p-5">
            <div>
              <h3 class="font-semibold text-white">{{ t(`home.templates.items.${tpl.key}.name`) }}</h3>
              <p class="mt-0.5 text-xs text-slate-400">{{ t(`home.templates.items.${tpl.key}.category`) }}</p>
            </div>
            <span class="text-xs font-medium text-brand-300 opacity-0 transition-opacity group-hover:opacity-100">
              {{ t('templates.use') }} →
            </span>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>
