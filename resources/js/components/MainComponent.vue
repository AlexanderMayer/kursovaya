<script setup>

</script>

<template>
    <div class="container border border-1 rounded">
        <nav>
            <router-link class="m-2" :to="{name: 'start'}">Start</router-link>
            <router-link class="m-2" :to="{name: 'test2'}">Test2</router-link>
            <router-link class="m-2" :to="{name: 'signUp'}">Регистрация</router-link>
            <router-link class="m-2" :to="{name: 'login'}">Войти в IT</router-link>
        </nav>
        <main>
            <router-view></router-view>
        </main>
    </div>
</template>

<style scoped>

</style>
