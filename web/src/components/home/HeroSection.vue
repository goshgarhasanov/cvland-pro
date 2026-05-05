<script setup lang="ts">
import { Sparkles, ArrowRight, Star } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

import CvMockup from './CvMockup.vue'

const { t } = useI18n()
</script>

<template>
  <section class="relative overflow-hidden">
    <!-- Background glows -->
    <div class="glow left-1/2 top-0 h-[480px] w-[480px] -translate-x-1/2 bg-brand-600/30"></div>
    <div class="glow right-0 top-40 h-[320px] w-[320px] bg-fuchsia-600/20"></div>
    <div class="absolute inset-0 -z-10 grid-bg mask-radial opacity-60"></div>

    <div class="mx-auto max-w-7xl px-6 pb-20 pt-24 sm:pt-32 lg:pb-28">
      <div class="grid items-center gap-16 lg:grid-cols-12">
        <div class="lg:col-span-7">
          <span class="kicker">
            <Sparkles class="h-3.5 w-3.5" />
            {{ t('home.badge') }}
          </span>

          <h1 class="mt-6 font-display text-4xl font-bold leading-[1.05] tracking-tight text-white sm:text-6xl lg:text-7xl">
            {{ t('home.hero_title_pre') }}
            <span class="gradient-text">{{ t('home.hero_title_accent') }}</span>
            {{ t('home.hero_title_post') }}
          </h1>

          <p class="mt-6 max-w-xl text-lg text-slate-400">
            {{ t('home.hero_subtitle') }}
          </p>

          <div class="mt-10 flex flex-wrap items-center gap-3">
            <RouterLink :to="{ name: 'auth.register' }" class="btn-primary text-base">
              {{ t('home.cta_primary') }}
              <ArrowRight class="h-4 w-4" />
            </RouterLink>
            <a href="#templates" class="btn-secondary text-base">
              {{ t('home.cta_secondary') }}
            </a>
          </div>

          <div class="mt-10 flex flex-wrap items-center gap-4">
            <div class="flex -space-x-2">
              <div
                v-for="i in 5"
                :key="i"
                class="h-9 w-9 rounded-full border-2 border-slate-950 bg-gradient-to-br"
                :class="[
                  i === 1 && 'from-pink-500 to-rose-500',
                  i === 2 && 'from-amber-400 to-orange-500',
                  i === 3 && 'from-emerald-400 to-teal-500',
                  i === 4 && 'from-sky-400 to-blue-500',
                  i === 5 && 'from-violet-500 to-purple-600',
                ]"
              />
            </div>
            <div>
              <div class="flex items-center gap-1 text-amber-400">
                <Star v-for="i in 5" :key="i" class="h-4 w-4 fill-current" />
              </div>
              <p class="mt-1 text-xs text-slate-400">{{ t('home.trust') }}</p>
            </div>
          </div>
        </div>

        <div class="relative lg:col-span-5">
          <CvMockup />
        </div>
      </div>
    </div>
  </section>
</template>
