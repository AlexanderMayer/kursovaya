<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const name = ref('');
const login = ref('');
const email = ref('');
const surname = ref('');

const nameError = ref('');
const surnameError = ref('');
const loginError = ref('');
const emailError = ref('');
const formError = ref('');
const showSuccessModal = ref(false);

const validateForm = () => {
    nameError.value = '';
    surnameError.value = '';
    loginError.value = '';
    emailError.value = '';
    formError.value = '';

    let isValid = true;

    if (!name.value) {
        nameError.value = 'Пожалуйста, введите имя.';
        isValid = false;
    }

    if (!surname.value) {
        surnameError.value = 'Пожалуйста, введите фамилию.';
        isValid = false;
    }

    if (!login.value) {
        loginError.value = 'Пожалуйста, введите логин.';
        isValid = false;
    } else if (!/^[a-zA-Z0-9]+$/.test(login.value)) {
        loginError.value = 'Логин может содержать только латинские буквы и цифры без пробелов и специальных символов.';
        isValid = false;
    }

    if (!email.value) {
        emailError.value = 'Пожалуйста, введите почту.';
        isValid = false;
    } else if (!/\S+@\S+\.\S+/.test(email.value)) {
        emailError.value = 'Пожалуйста, введите корректный адрес электронной почты.';
        isValid = false;
    }

    return isValid;
};

const clearForm = () => {
    name.value = '';
    login.value = '';
    email.value = '';
    surname.value = '';
};

const signUp = async () => {
    formError.value = '';
    if (!validateForm()) return;

    try {
        const response = await axios.post(
            'api/user/store', {
                name: name.value,
                login: login.value,
                email: email.value,
                surname: surname.value,
            }
        );
        clearForm();
        showSuccessModal.value = true;

        setTimeout(() => {
            showSuccessModal.value = false;
            router.push({ name: 'login' });
        }, 5000);

    } catch (error) {
        console.error('Ошибка при регистрации', error);
        formError.value = 'Произошла ошибка при регистрации. Попробуйте снова.';
    }
};

</script>

<template>
    <div class="container mt-4">
        <div class="w-50 mx-auto">
            <h3>Регистрация</h3>
            <input type="text" v-model="name" class="form-control my-3" placeholder="Имя">
            <span v-if="nameError" class="text-danger">{{ nameError }}</span>

            <input type="text" v-model="surname" class="form-control my-3" placeholder="Фамилия">
            <span v-if="surnameError" class="text-danger">{{ surnameError }}</span>

            <input type="text" v-model="login" class="form-control my-3" placeholder="Логин">
            <span v-if="loginError" class="text-danger">{{ loginError }}</span>

            <input type="email" v-model="email" class="form-control my-3" placeholder="Почта">
            <span v-if="emailError" class="text-danger">{{ emailError }}</span>

            <input type="submit" @click.prevent="signUp" class="btn btn-success w-100" value="Зарегистрироваться">
            <span v-if="formError" class="text-danger mt-3">{{ formError }}</span>
        </div>

        <div v-if="showSuccessModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Регистрация успешна</h5>
                    </div>
                    <div class="modal-body">
                        <p>Письмо с паролем выслано вам на почту!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal.fade.show.d-block {
    opacity: 1;
    transition: opacity 1s ease-in-out;
}
</style>
