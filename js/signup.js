"use strict";

const signup = async () => {
    const url = "http://localhost:8000/user/signup";
    const user = {
        user_name: "Ghs Julian",
        user_phone: "01743789311",
        user_email: "ghsjulian@gmail.com",
        user_password: "123456",
        user_token : "xxxx"
    };
    try {
        const sendData = await fetch(url, {
            method: "POST",
            headers: { "content-type": "application/json" },
            body: JSON.stringify(user)
        });
        const response = await sendData.json();
        console.log(response);
    } catch (error) {
        console.log(error);
    }
};

signup();
