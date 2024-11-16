## AIBISO Hub API Documentation 🌐🚀

Abisohub is a value-added service platform that provides individuals and businesses with access to airtime, data bundles, electricity bills, cable TV, alpha top-up, Smile subscription, and exam pins.

- 💳 Airtime
- 📶 Data Bundles
- ⚡ Electricity Bills
- 📺 Cable TV Subscriptions
- 🔋 Smile Top-ups
- 📘 Exam PINs

This documentation will guide you through the various endpoints, explaining the request structure, expected responses, and error handling.

---

### 🌐 Global Error Response for Invalid Token

In case of an invalid or missing token, the following response is returned:
```json
{
    "status": "fail",
    "msg": "Authorization token not found 20240927"
}
```
> All token names are `"Token"` and must be added to the `Bearer` header.

---

### 📝 Registration – User sign-up to web/mobile App

**Endpoint**: `{{baseUrl}}/account/signup`  
**Method**: POST  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Payload**:
```json
{
    "account": " ",
    "email": "mckaka@gmail.com",
    "fname": "Mobile",
    "lname": "App",
    "password": "Insomnia1#",
    "phone": "08033446699",
    "referal": " ",
    "state": "Adamawa",
    "transpin": "6688"
}
```

**Success Response**:
```json
{
    "status": "success",
    "msg": "Registration successful",
    "apiKey": "ECmBpf7F2CAhCbe5tI3xda168Cwdz9DGrs219CCxAnBc3CJAx5H3C6C3A2Ax1728061156",
    "token": null
}
```

**Fail Response**:
```json
{
    "status": "error",
    "msg": "Phone Number Already Exist"
}
```

---


### 🔑 Login

**Endpoint**: `{{baseUrl}}/account/login/`  
**Method**: POST  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Payload**:
```json
{
    "phone": "08000003333",
    "accesspass": "final_bos"
}
```

**Success Response**:
```json
{
    "status": "success",
    "msg": "Login Successful",
    "name": "Final Test",
    "phone": "08000003333",
    "apiKey": "96GhJ3ayrc2Bf158xsCC2ABt2CxC8qpgHcCCvBxkAC3dbnB6CIxiwb15z4Al1723739914",
    "userId": "23"
}
```

**Fail Response**:
```json
{
    "status": "invalid",
    "msg": "Incorrect Details"
}
```
---

### 🔐 Get Password Reset PIN

- **Endpoint:** `/account/recover`
- **Method:** `POST`
- **Token:** `20241004`

#### Request Payload:
```json
{
  "email": "mcka@gmail.com",
  "isApiRequest": true
}
```

#### Success Response:
```json
{
  "status": 0,
  "code": 2657
}
```

#### Error Response:
```json
{
  "status": 1
}
```

---

### 🔄 Reset Password

- **Endpoint:** `/account/verify`
- **Method:** `POST`
- **Token:** `20241004`

#### Request Payload:
```json
{
  "email": "mcka@gmail.com",
  "code": 2657,
  "password": "final_boss"
}
```

#### Success Response:
```json
{
  "status": "success",
  "msg": "Password changed successfully"
}
```

#### Error Response:
```json
{
  "error": "Invalid code"
}
```

---


### 👤 Get User Details

**Endpoint**: `{{baseUrl}}/user`  
**Method**: GET  
**API Token**: Requires the token received during registration.

**Description**: This endpoint retrieves the user's name and wallet balance.

**Success Response**:
```json
{
    "name": "Test Final",
    "balance": "0.00",
    "status": "success"
}
```

**Fail Response**:
```json
{
    "status": "fail",
    "msg": "Authorization token not found"
}
```

---

### 📜 Get User Transaction History

**Endpoint**: `{{baseUrl}}/transactions/`  
**Method**: GET  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Payload**:
```json
{
    "userId": 9,
    "limit": 3
}
```

**Success Response** (Sample):
```json
[
    {
        "tId": 1,
        "sId": 9,
        "transref": "19891721653024",
        "servicename": "Electricity Bill",
        "servicedesc": "Purchase of Kano Electric (prepaid) Meter Unit of N200 for Meter Number: 58000041838",
        "amount": "200",
        "status": 1,
        "oldbal": "5795",
        "newbal": "5795",
        "profit": 0,
        "date": "2024-07-22 13:57:04",
        "api_response_log": "BELOW MINIMUM AMOUNT ALLOWED"
    }
]
```

**Fail Response**:
```json
"No transactions found for user."
```

---

### 📶 Get Airtime Plans for Certain Providers

**Endpoint**: `{{baseUrl}}/airtime/plan/2/networkId/2`  
**Method**: GET  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Description**: Retrieves airtime plans for a specific network using the route.

**Success Response**:
```json
{
    "0": {
        "aId": 2,
        "aNetwork": "2",
        "aBuyDiscount": 97,
        "aUserDiscount": 99,
        "aAgentDiscount": 98,
        "aVendorDiscount": 98,
        "aType": "VTU"
    },
    "status": "success"
}
```

**Fail Response**:
```json
{
    "status": "success"
}
```

---

### 📊 Get Data Plans

**Endpoint**: `{{baseUrl}}/data/dataplans/2`  
**Method**: GET  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Description**: Fetches data plans for a specific network when the network ID is passed. Omitting the ID fetches plans for all networks.

### Route Behavior Table
| Route                          | Response Type    | Sample Value                                                                 |
|---------------------------------|------------------|-------------------------------------------------------------------------------|
| `/data/dataplans/2`             | Specific network | Data plans for network ID 2                                                   |
| `/data/dataplans/` (No ID)      | All networks     | All data plans for all networks                                               |

**Success Response**:
```json
{
    "0": {
        "pId": 37,
        "name": "1.8GB = 800MB(DAY) + 1GB(NIGHT)",
        "price": "470",
        "userprice": "470",
        "agentprice": "470",
        "vendorprice": "470",
        "planid": "37",
        "type": "Gifting",
        "datanetwork": 2,
        "day": "30"
    }
}
```

**Fail Response**:
```json
{
    "status": "success"
}
```

---

### 📡 Get Networks

**Endpoint**: `{{baseUrl}}/data/networks/`  
**Method**: GET  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Description**: Fetches all network provider details.

### Route Behavior Table
| Route                         | Response Type    | Sample Value                                                               |
|-------------------------------|------------------|----------------------------------------------------------------------------|
| `/data/networks/2`             | Specific network | Details of the network provider with ID 2                                   |
| `/data/networks/` (No ID)      | All networks     | Details of all network providers                                            |

**Success Response**:
```json
{
    "0": {
        "networkId": 2,
        "networkName": "MTN",
        "status": "On"
    }
}
```

**Fail Response**:
```json
{
    "status": "fail"
}
```
---

### ⚡ Get List of All Electric Distribution Companies

**Endpoint**: `{{baseUrl}}/electricity/plans`  
**Method**: GET  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Description**: Retrieves the list of all electric distribution companies.

### Route Behavior Table
| Route                        | Response Type       | Sample Value                                                         |
|------------------------------|---------------------|----------------------------------------------------------------------|
| `/electricity/plans/2`       | Specific provider    | Details of the electric provider with ID 2                           |
| `/electricity/plans/` (No ID)| All providers       | Details of all electric distribution companies                        |

**Success Response**:
```json
{
	"0": {
		"eId": 1,
		"electricityid": "ikeja-electric",
		"provider": "Ikeja Electric",
		"abbreviation": "IE",
		"providerStatus": "On",
		"electricity_charges": 0
	},
	"1": {
		"eId": 2,
		"electricityid": "eko-electric",
		"provider": "Eko Electric",
		"abbreviation": "EKEDC",
		"providerStatus": "On",
		"electricity_charges": 0
	},
	"2": {
		"eId": 3,
		"electricityid": "kano-electric",
		"provider": "Kano Electric",
		"abbreviation": "KEDCO",
		"providerStatus": "On",
		"electricity_charges": 0
	},
	"3": {
		"eId": 4,
		"electricityid": "portharcourt-electric",
		"provider": "Port Harcourt Electric",
		"abbreviation": "PHEDC",
		"providerStatus": "On",
		"electricity_charges": 0
	},
	"status": "success"
}
```

**Failure Response**:
```json
{
	"status": "fail" // Returns empty if a non-existent vendor ID is passed.
}
```

---

### 📺 Get List of Cable TV Subscriptions and Subscription Plans

**Endpoint**: `{{baseUrl}}/cabletv/plans/`  
**Method**: GET  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Description**: Fetches the list of cable TV subscriptions and available plans.

### Route Behavior Table
| Route                          | Response Type           | Sample Value                                                          |
|--------------------------------|-------------------------|----------------------------------------------------------------------|
| `/cabletv/plans/2`             | Subscription plans      | Returns the subscription plans for the provider with ID 2 (DSTV)   |
| `/cabletv/plans/` (No ID)      | All providers           | Returns the list of all cable TV providers                           |

**Success Response (Without plan ID)**:
```json
{
	"0": {
		"cId": 1,
		"cableid": "1",
		"provider": "GOTV",
		"providerStatus": "On"
	},
	"1": {
		"cId": 2,
		"cableid": "2",
		"provider": "DSTV",
		"providerStatus": "On"
	},
	"2": {
		"cId": 3,
		"cableid": "3",
		"provider": "STARTIMES",
		"providerStatus": "On"
	},
	"status": "success"
}
```

**Success Response (With plan ID, e.g., /plan/2)**:
```json
{
	"0": {
		"cId": 2,
		"cableid": "2",
		"provider": "DSTV",
		"providerStatus": "On",
		"cpId": 9,
		"name": "DStv Padi N3,600",
		"price": "3600",
		"userprice": "3600",
		"agentprice": "3600",
		"vendorprice": "3600",
		"planid": "dstv-padi",
		"type": null,
		"cableprovider": 2,
		"day": "30"
	},
	"1": {
		"cId": 2,
		"cableid": "2",
		"provider": "DSTV",
		"providerStatus": "On",
		"cpId": 10,
		"name": "DStv Yanga N5,100",
		"price": "5100",
		"userprice": "5100",
		"agentprice": "5100",
		"vendorprice": "5100",
		"planid": "dstv-yanga",
		"type": null,
		"cableprovider": 2,
		"day": "30"
	},
	"2": {
		"cId": 2,
		"cableid": "2",
		"provider": "DSTV",
		"providerStatus": "On",
		"cpId": 11,
		"name": "Dstv Confam N9,300",
		"price": "9300",
		"userprice": "9300",
		"agentprice": "9300",
		"vendorprice": "9300",
		"planid": "dstv-confam",
		"type": null,
		"cableprovider": 2,
		"day": "30"
	},
	"status": "success"
}
```

**Failure Response**:
```json
{
	"status": "fail" // Returns empty if a non-existent provider ID is passed.
}
```

---

### 📚 Get Exam Pins

**Endpoint**: `{{baseUrl}}/exam/type/`  
**Method**: GET  
**API Token**: Programmatically generated as `YYYYMMDD` (example: 20241004)

**Description**: Retrieves the list of exam pin providers.

### Route Behavior Table
| Route                        | Response Type        | Sample Value                                                      |
|------------------------------|----------------------|------------------------------------------------------------------|
| `/exam/type/2`               | Specific provider     | Details of the exam pin provider with ID 2                      |
| `/exam/type/` (No ID)       | All providers        | Details of all exam pin providers                                |

**Success Response (With provider ID, e.g., /type/2)**:
```json
{
	"0": {
		"eId": 2,
		"examid": "2",
		"provider": "NECO",
		"price": 800,
		"buying_price": 0,
		"providerStatus": "On"
	},
	"status": "success"
}
```

**Success Response (Without provider ID)**:
```json
{
	"0": {
		"eId": 1,
		"examid": "1",
		"provider": "WAEC",
		"price": 1800,
		"buying_price": 0,
		"providerStatus": "On"
	},
	"1": {
		"eId": 2,
		"examid": "2",
		"provider": "NECO",
		"price": 800,
		"buying_price": 0,
		"providerStatus": "On"
	},
	"2": {
		"eId": 3,
		"examid": "3",
		"provider": "NABTEB",
		"price": 950,
		"buying_price": 0,
		"providerStatus": "On"
	},
	"status": "success"
}
```

**Failure Response**:
```json
{
	"status": "fail" // If provider ID does not exist, a success response is returned with no data.
}
```
