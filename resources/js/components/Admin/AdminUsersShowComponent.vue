<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Vue3Select from 'vue3-select';
import 'vue3-select/dist/vue3-select.css';

const router = useRouter();
let users = ref([]);
const searchQuery = ref('');

const data = async () => {
    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const response = await axios.post('http://localhost/kurs2.2/public/api/admin/users', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        users.value = response.data;
    } catch (error) {
        console.error('Ошибка вывода пользователей', error);
        throw error;
    }
};

const filteredUsers = computed(() => {
    if (!searchQuery.value) return users.value;
    return users.value.filter(user =>
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.surname.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.login.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

onMounted(() => {
    data();
});
</script>

<template>
    <div class="container">
        <h2 class="my-4">Список пользователей</h2>
        <input type="text" v-model="searchQuery" placeholder="Для поиска пользователя введите имя или фамилию или логин или почту..." class="form-control mb-3"/>
        <div class="row">
            <div class="user-item" v-for="user in filteredUsers" :key="user.id">
                <span>Имя: {{ user.name }}</span>
                <span> Фамилия: {{ user.surname }}</span>
                <span> Логин: {{ user.login }}</span>
                <span> Почта: {{ user.email }}</span>
                <span v-show="user.role_id === 1 "> Роль: Пользователь</span>
                <span v-show="user.role_id === 2 "> Роль: Администратор</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.user-item {
    display: inline-block;
    margin: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.container {
    max-width: 1820px;
}
</style>
