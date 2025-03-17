<script setup>
import { Head, router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import DialogBase from '@/components/DialogBase.vue'

const props = defineProps({ game: Object, user: Object })

const cellsRef = ref([])

const statusRef = ref(null)

const themeToggleRef = ref(null)

const waitPlayerDialog = ref(true)

const winnerDialog = ref(false)

const winningCombinations = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8], // Linhas
    [0, 3, 6], [1, 4, 7], [2, 5, 8], // Colunas
    [0, 4, 8], [2, 4, 6]             // Diagonais
]

const gameData = ref({
    board: [],
    currentPlayer: '',
    movesX: [],
    movesO: [],
    gameActive: true
})

function handleCellClick(cell) {
    const index = cellsRef.value.indexOf(cell)

    console.log(gameData.value.gameActive)
    console.log(gameData.value.board[index])
    if ((gameData.value.board[index] && gameData.value.board[index] !== '') ||
        !gameData.value.gameActive ||
        !isPlayerTurn()) return

    gameData.value.board[index] = gameData.value.currentPlayer
    cell.textContent = gameData.value.currentPlayer
    cell.classList.remove('text-primary', 'text-secondary')
    cell.classList.add(gameData.value.currentPlayer === 'X' ? 'text-primary' : 'text-secondary')

    if (gameData.value.currentPlayer === 'X') {
        gameData.value.movesX.push(index);
        if (gameData.value.movesO.length === 3) {
            const oldMove = gameData.value.movesO.shift();
            gameData.value.board[oldMove] = '';
            cellsRef.value[oldMove].textContent = '';
        }
    } else {
        gameData.value.movesO.push(index);
        if (gameData.value.movesX.length === 3) {
            const oldMove = gameData.value.movesX.shift();
            gameData.value.board[oldMove] = '';
            cellsRef.value[oldMove].textContent = '';
        }
    }

    if (checkWin()) {
        updateGame()
        return
    }

    gameData.value.currentPlayer = gameData.value.currentPlayer === 'X' ? 'O' : 'X'

    updateGame()
}

function checkWin() {
    const isWin = winningCombinations.some(combination => {
        return combination.every(index => gameData.value.board[index] === gameData.value.currentPlayer)
    })

    if (isWin) {
        statusRef.value.textContent = `Jogador ${gameData.value.currentPlayer} venceu!`
        gameData.value.gameActive = false
        winnerDialog.value = true
    }

    return isWin
}

function resetClick (currPlayer = 'X') {
    gameData.value.board = ['', '', '', '', '', '', '', '', '']
    gameData.value.gameActive = true
    gameData.value.currentPlayer = currPlayer
    gameData.value.movesX = []
    gameData.value.movesO = []
    statusRef.value.textContent = 'Vez do jogador: '
    cellsRef.value.forEach(cell => (cell.textContent = ''))
    winnerDialog.value = false

    updateGame()
}

function themeToggle () {
    document.documentElement.classList.toggle('dark')
    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')
}

function updateGame() {
    const data = {
        board: gameData.value.board,
        current_player: gameData.value.currentPlayer,
        moves_x: gameData.value.movesX,
        moves_o: gameData.value.movesO,
        is_active: gameData.value.gameActive
    }

    router.put(`/games/${props.game.id}`, data)
}

async function copyGameId () {
    try {
        await navigator.clipboard.writeText(props.game.id);
    } catch (error) {
        console.error(error.message);
    }
}

function updateUi() {
    gameData.value.board.forEach((cell, index) => {
        cellsRef.value[index].textContent = cell
        cellsRef.value[index].classList.remove('text-primary', 'text-secondary')
        cellsRef.value[index].classList.add(cell === 'X' ? 'text-primary' : 'text-secondary')
    })

    if (gameData.value.gameActive && winnerDialog.value) {
        resetClick()
        winnerDialog.value = false
    }

    checkWin()
}

function isPlayerTurn() {
    return ((gameData.value.currentPlayer === 'X') ? props.game.player_x_id : props.game.player_o_id) === props.user.id
}

onMounted(() => {
    waitPlayerDialog.value = !props.game.player_o_id

    gameData.value.board = props.game.board
    gameData.value.currentPlayer = props.game.current_player
    gameData.value.movesX = props.game.moves_x
    gameData.value.movesO = props.game.moves_o
    gameData.value.gameActive = props.game.is_active

    gameData.value.gameActive = !waitPlayerDialog.value

    updateUi()

    Echo.private(`game.${props.game.id}`)
        .listen('GameUpdateEvent', (e) => {
            waitPlayerDialog.value = !e.game.player_o_id

            gameData.value.board = e.game.board
            gameData.value.currentPlayer = e.game.current_player
            gameData.value.movesX = e.game.moves_x
            gameData.value.movesO = e.game.moves_o
            gameData.value.gameActive = e.game.is_active

            updateUi()
        })
})
</script>

<template>
    <Head title="TicTacToe"></Head>
    <div class="w-[400px] space-y-12" :class="{ 'pointer-events-none': waitPlayerDialog }">
        <!-- Header -->
        <div class="flex justify-center items-center">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                Jogo da Velha
            </h1>
            <button ref="themeToggleRef" class="ml-8 -p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" @click="themeToggle">
                <svg id="sun-icon" class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg id="moon-icon" class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        <!-- Game Status -->
        <div class="text-center">
            <p ref="statusRef" class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ isPlayerTurn() ? 'Sua vez!' : 'Vez do outro jogador' }}</p>
            <button id="reset-btn" class="mt-2 px-4 py-2 bg-accent text-white rounded-lg hover:bg-opacity-90 transition-colors" @click="resetClick">Reiniciar Jogo</button>
        </div>

        <!-- Game Board -->
        <div class="grid grid-cols-3 gap-3 mx-auto">
            <div
                class="aspect-square bg-white dark:bg-gray-800 rounded-md shadow-lg flex items-center justify-center text-8xl font-bold cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                v-for="item in 9"
                ref="cellsRef"
                :key="item"
                @click="handleCellClick($event.target)"
            ></div>
        </div>

        <div>
            {{ gameData }}
        </div>
    </div>

    <dialog-base :dialogVisible="waitPlayerDialog">
        <div
            data-dialog="dialog"
            class="relative m-4 p-6 w-2/5 min-w-[40%] max-w-[40%] rounded-lg bg-white dark:bg-gray-800 shadow-sm"
        >
            <div class="flex shrink-0 items-center pb-4 text-2xl font-bold text-slate-800 dark:text-slate-200">
                Esperando o outro jogador
            </div>
            <div class="relative border-t border-slate-600 py-4 leading-normal text-slate-600 dark:text-slate-100">
                Compartilhe o ID do jogo com seu amigo e espere ele conectar...
            </div>
            <div class="py-4 leading-normal text-slate-600 dark:text-slate-100 flex flex-row">
                <div class="p-2 rounded-md dark:bg-gray-700">{{ game.id }}</div>
                <button class="px-2 py-1 ml-2 bg-secondary text-white text-base rounded-lg hover:bg-opacity-90 transition-colors" @click="copyGameId">Copiar</button>
            </div>

        </div>
    </dialog-base>

    <dialog-base :dialogVisible="winnerDialog">
        <div
            data-dialog="dialog"
            class="relative m-4 p-6 w-2/5 min-w-[40%] max-w-[40%] rounded-lg bg-white dark:bg-gray-800 shadow-sm"
        >
            <div class="flex shrink-0 items-center pb-4 text-2xl font-bold text-slate-800 dark:text-slate-200">
                Fim de Jogo
            </div>
            <div class="relative border-t border-slate-600 py-4 leading-normal text-slate-600 dark:text-slate-100">
                {{ isPlayerTurn() ? `Parabéns ${user.name}, você venceu!` : `Que pena ${user.name}, você perdeu!` }}
            </div>
            <div class="py-4 leading-normal text-slate-600 dark:text-slate-100 flex flex-row justify-end">
                <button class="px-2 py-1 ml-2 bg-secondary text-white text-base rounded-lg hover:bg-opacity-90 transition-colors" @click="resetClick(gameData.currentPlayer === 'X' ? 'O' : 'X')">Jogar outra vez</button>
            </div>

        </div>
    </dialog-base>

</template>
