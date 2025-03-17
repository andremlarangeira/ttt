<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({ user: Object })

const jogoIdDialog = ref(false)

const userName = ref('')

const gameId = ref('')

const showUserNameInput = ref(false)

function toggleDialog() {
    jogoIdDialog.value = !jogoIdDialog.value
}

function entrarJogo() {
    router.put('/games/' + gameId.value)
}

function updateUserName() {
    router.post('/users/' + props.user.id, {
        name: userName.value
    })

    showUserNameInput.value = false
}

function editUserName() {
    userName.value = props.user.name
    showUserNameInput.value = true
}

</script>

<template>
    <Head title="Menu"></Head>
    <div class="w-[600px] mt-12">

        <!-- Game Status -->
        <div class="relative text-center flex flex-col items-center space-y-4 pb-12">
            <div class="bg-gradient-to-r mb-12 text-4xl font-bold from-primary to-secondary bg-clip-text text-transparent">VELHA INFINITA</div>
            <Link class="mt-2 px-4 py-2 bg-accent text-white text-2xl rounded-lg hover:bg-opacity-90 transition-colors" href="/games" method="post" as="button">Novo Jogo</Link>
            <button class="mt-2 px-4 py-2 bg-accent text-white text-2xl rounded-lg hover:bg-opacity-90 transition-colors" @click="toggleDialog">Entrar em um Jogo</button>
            <div class="absolute bottom-0 right-6">
                <div class="relative">
                    <div @click="editUserName" class="cursor-pointer">{{ user.name }}</div>
                    <div class="absolute top-0 left-0 flex items-center space-x-2" v-show="showUserNameInput">
                        <input type="text" class="w-[120px] px-2 py-1 border dark:bg-slate-700 dark:text-slate-200 border-slate-600 rounded-lg" placeholder="ID do jogo" v-model="userName" />
                        <button class="px-2 py-1 bg-secondary text-white text-base rounded-lg hover:bg-opacity-90 transition-colors" @click="updateUserName">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div
        class="fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black bg-opacity-40 backdrop-blur-sm transition-opacity duration-300"
        v-if="jogoIdDialog"
    >
        <div
            data-dialog="dialog"
            class="relative m-4 p-6 w-2/5 min-w-[40%] max-w-[40%] rounded-lg bg-white dark:bg-gray-800 shadow-sm"
        >
            <div class="flex shrink-0 items-center pb-4 text-2xl font-bold text-slate-800 dark:text-slate-200">
                Digite o ID do jogo
            </div>
            <div class="relative border-t border-slate-600 py-4 leading-normal text-slate-600 dark:text-slate-100">
                <input type="text" class="w-full px-3 py-2 border dark:bg-slate-700 dark:text-slate-200 border-slate-600 rounded-lg" placeholder="ID do jogo" v-model="gameId" />
            </div>
            <div class="flex justify-end space-x-4">
                <button class="mt-2 px-3 py-2 text-primary text-xl rounded-lg transition-colors" @click="toggleDialog">Cancelar</button>
                <button class="mt-2 px-3 py-2 bg-secondary text-white text-xl rounded-lg hover:bg-opacity-90 transition-colors" @click="entrarJogo">Entrar</button>
            </div>
        </div>
    </div>

</template>
