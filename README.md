The application provides 2 API endpoints;
-/api/v1/orders which returns all the orders and their details depending on the logged user role (optional page and per_page parameters for pagination handling),
-/api/v1/orders/{id} which returns details about specific order depending on the logged user role.

App provides 2 types of users - Employee and Admin whose get different API results.

/orders api example as an Admin:
![image](https://github.com/user-attachments/assets/67179117-78e0-4a4b-8633-6acd9a9b3bcc)
![image](https://github.com/user-attachments/assets/3fbe14f9-0898-4b04-8d04-91906086cf55)

/orders/77 (id 77) example as an Admin:
![image](https://github.com/user-attachments/assets/99ab5e44-636b-4ce8-92af-22b3627067a6)
![image](https://github.com/user-attachments/assets/75017f4b-7148-4396-bc89-c85a74817072)

/orders api example as an Employee:
![image](https://github.com/user-attachments/assets/4dbee33a-3675-4654-886e-3eaa4dce5dc1)
![image](https://github.com/user-attachments/assets/4568944d-9ec4-4f95-be27-61688e946104)

/orders/77 (id 77) example as an Employee:
![image](https://github.com/user-attachments/assets/72e44241-8370-4103-aebd-60ea56723e58)
![image](https://github.com/user-attachments/assets/dc593597-0722-4677-9a78-491a39aaf0ef)


further explanation and details (in polish) are expected to be visible in the downloadable document the link of which we can find below:
https://docs.google.com/document/d/1AtTFXdpJthcuAW_msfbE9IdQeds5LYki
