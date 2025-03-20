<template>
  <div class="relative w-full min-h-[36px] shadow-md">
    <div
    class="absolute z-0 size-full rounded-lg p-2 px-4 ring-2 ring-slate-200 dark:ring-slate-800"
    />
    <div
      class="btn-base absolute z-1 size-full flex flex-row cursor-pointer items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-800 p-2 px-4 text-xl text-slate-800 dark:text-slate-200 font-bold hover:-translate-x-1 hover:-translate-y-1"
    >
      <div>
        <slot />
      </div>
    </div>
  </div>
</template>

<style>
.btn-base {
  transition: all 0.2s;
}
</style>
