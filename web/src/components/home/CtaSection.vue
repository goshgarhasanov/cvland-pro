<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

const { t } = useI18n()
</script>

<template>
  <section class="px-6 py-24">
    <div class="relative mx-auto max-w-5xl overflow-hidden rounded-3xl border border-brand-500/30 bg-gradient-to-br from-brand-700 via-brand-600 to-fuchsia-700 p-10 shadow-2xl shadow-brand-900/40 sm:p-16">
      <!-- Decorative circles -->
      <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
      <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-fuchsia-400/20 blur-3xl"></div>

      <div class="relative mx-auto max-w-2xl text-center">
        <h2 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl md:text-5xl">
          {{ t('home.cta.title') }}
        </h2>
        <p class="mx-auto mt-5 max-w-xl text-base text-brand-100 sm:text-lg">
          {{ t('home.cta.subtitle') }}
        </p>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
          <RouterLink :to="{ name: 'auth.register' }" class="btn bg-white px-6 py-3 text-base text-brand-700 shadow-xl hover:bg-brand-50">
            {{ t('home.cta.primary') }}
            <ArrowRight class="h-4 w-4" />
          </RouterLink>
          <a href="#templates" class="btn px-6 py-3 text-base text-white ring-1 ring-white/30 hover:bg-white/10">
            {{ t('home.cta.secondary') }}
          </a>
        </div>
      </div>
    </div>
  </section>
</template>
