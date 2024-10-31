<script setup>
import {onMounted, ref} from 'vue';
import Cookies from "js-cookie";
import axios from "axios";
import {useRoute} from 'vue-router';
import {thisUrl} from "../../api.js";

const route = useRoute();
let user = ref([]);
let showBanConfirm = ref(false);
let showBanSuccess = ref(false);

const data = async () => {
    try {
        const userId = route.params.id;
        const token = Cookies.get('token');
        const response = await axios.post(`${thisUrl()}/admin/users/${userId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = response.data;
        const avatarDefault = 'uploads/avatar.png';
        if (!user.value.avatar) {
            user.value.avatar = avatarDefault;
        }
    } catch (error) {
        console.error('Ошибка вывода', error);
    }
};

const confirmBan = () => {
    showBanConfirm.value = true;
};

const userBan = async (userId) => {
    try {
        const token = Cookies.get('token');
        await axios.post(`${thisUrl()}/admin/users/${userId}/ban`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        showBanConfirm.value = false;
        showBanSuccess.value = true;
        await data();
        setTimeout(() => {
            showBanSuccess.value = false;
        }, 3000);
    } catch (error) {
        console.error('Ошибка при блокировке пользователя', error);
        alert('Ошибка при блокировке пользователя, попробуйте ещё раз!');
    }
};

onMounted(() => {
    data();
});
</script>

<template>
    <div class="container my-4" v-show="user">
        <div class="row">
            <!-- User Info -->
            <div class="col-md-3 d-flex align-items-center">
                <div class="w-100">
                    <div>
                        <img class="rounded user-avatar" :src="`../../storage/${user.avatar}`" alt="Фото пользователя">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3>{{ user.name }} {{ user.surname }}</h3>
                <p><strong>Логин:</strong> {{ user.login }}</p>
                <div class="my-3">
                    <h5>Рейтинг пользователя:</h5>
                    <div class="progress">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            :class="{
                                'bg-danger': user.rate_decency <= 25,
                                'bg-warning': user.rate_decency > 25 && user.rate_decency <= 50,
                                'bg-info': user.rate_decency > 50 && user.rate_decency <= 75,
                                'bg-success': user.rate_decency > 75
                            }"
                            :style="{ width: user.rate_decency + '%' }"
                            :aria-valuenow="user.rate_decency"
                            aria-valuemin="0"
                            aria-valuemax="100">
                            {{ user.rate_decency }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-evenly" v-if="user.activity === 'active'">
                <button @click.prevent="confirmBan" class="btn btn-danger mb-2">Заблокировать</button>
            </div>
        </div>
    </div>


    <div v-if="showBanConfirm" class="modal-overlay">
        <div class="modal-content">
            <p>Вы уверены, что хотите заблокировать пользователя?</p>
            <button @click="userBan(user.id)" class="btn btn-danger mb-2">Заблокировать</button>
            <button @click="showBanConfirm = false" class="btn btn-secondary">Отмена</button>
        </div>
    </div>


    <div v-if="showBanSuccess" class="modal-overlay">
        <div class="modal-content">
            <p>Пользователь успешно заблокирован</p>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 1820px;
}

.progress {
    max-width: 300px;
    height: 25px;
    border-radius: 0.5rem;
}

.progress-bar {
    line-height: 25px;
    font-size: 1rem;
}

h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.user-avatar {
    width: 100%;
    height: auto;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    max-width: 400px;
}

button.btn {
    margin: 0 10px;
}
</style>
