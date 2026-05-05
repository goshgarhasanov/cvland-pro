<script setup lang="ts">
import { ArrowLeft, Check } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'
import { RouterLink, RouterView } from 'vue-router'

const { t } = useI18n()

const perks = [
  'auth.perks.free',
  'auth.perks.ats',
  'auth.perks.ai',
]
</script>

<template>
  <div class="relative min-h-screen overflow-hidden bg-slate-950">
    <!-- Background glow -->
    <div class="pointer-events-none absolute inset-0 -z-10">
      <div class="absolute left-1/2 top-0 h-[600px] w-[600px] -translate-x-1/2 rounded-full bg-brand-600/20 blur-3xl"></div>
      <div class="absolute right-0 bottom-0 h-[400px] w-[400px] rounded-full bg-fuchsia-700/15 blur-3xl"></div>
      <div class="absolute inset-0 grid-bg mask-radial opacity-40"></div>
    </div>

    <div class="grid min-h-screen lg:grid-cols-2">
      <!-- Left: form -->
      <div class="flex flex-col px-6 py-8">
        <header class="flex items-center justify-between">
          <RouterLink :to="{ name: 'home' }" class="flex items-center gap-2 text-lg font-display font-bold text-white">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-sm font-bold shadow-lg shadow-brand-600/30">
              CV
            </span>
            CVLAND <span class="font-light text-brand-300">PRO</span>
          </RouterLink>
          <RouterLink :to="{ name: 'home' }" class="btn-ghost text-sm">
            <ArrowLeft class="h-4 w-4" />
            {{ t('common.back_home') }}
          </RouterLink>
        </header>

        <main class="flex flex-1 items-center justify-center py-12">
          <div class="w-full max-w-md">
            <RouterView />
          </div>
        </main>
      </div>

      <!-- Right: visual / quote (hidden on mobile) -->
      <aside class="relative hidden flex-col justify-between overflow-hidden border-l border-slate-800 bg-gradient-to-br from-slate-900 via-brand-950/40 to-slate-950 p-12 lg:flex">
        <div class="absolute -right-20 top-1/3 h-80 w-80 rounded-full bg-brand-500/20 blur-3xl"></div>
        <div class="absolute -left-20 bottom-0 h-72 w-72 rounded-full bg-fuchsia-600/15 blur-3xl"></div>

        <div class="relative z-10">
          <span class="kicker">{{ t('app.name') }}</span>
          <h2 class="mt-6 font-display text-3xl font-bold leading-tight text-white">
            {{ t('app.tagline') }}
          </h2>
          <p class="mt-4 max-w-md text-base text-slate-400">
            {{ t('home.hero_subtitle') }}
          </p>

          <ul class="mt-10 space-y-4">
            <li v-for="perk in perks" :key="perk" class="flex items-center gap-3">
              <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand-500/20 text-brand-300">
                <Check class="h-4 w-4" />
              </span>
              <span class="text-sm font-medium text-slate-200">{{ t(perk) }}</span>
            </li>
          </ul>
        </div>

        <figure class="relative z-10 rounded-2xl border border-slate-800 bg-slate-900/60 p-6 backdrop-blur">
          <blockquote class="text-sm leading-relaxed text-slate-300">
            &ldquo;İki həftə ərzində üç müsahibə dəvəti aldım. CVLAND PRO həqiqətən fərq yaradır.&rdquo;
          </blockquote>
          <figcaption class="mt-4 flex items-center gap-3">
            <div class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-fuchsia-500 text-xs font-bold text-white">
              AM
            </div>
            <div>
              <div class="text-sm font-semibold text-white">Aysu Məmmədova</div>
              <div class="text-xs text-slate-400">Frontend Developer, Bakı</div>
            </div>
          </figcaption>
        </figure>
      </aside>
    </div>
  </div>
</template>
