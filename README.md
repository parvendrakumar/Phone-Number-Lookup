# Phone Number Lookup

A **professional and responsive web application** to validate and retrieve details of a phone number using the **NumVerify API**.

This project provides a clean interface for looking up phone numbers with information such as:

* International and local number format
* Country name and code
* Carrier
* Location
* Line type (mobile, landline, VOIP)
* Validity of the number

---

## Features

* **Professional design** with responsive layout
* **Modern card-based UI** with loading spinner
* **Error handling** for invalid input and network issues
* **NumVerify API integration** for accurate number validation
* **Supports multiple countries**
* Easy **frontend-backend separation** with `HTML + CSS + JS` and `PHP` backend

---

## Demo

<img width="1366" height="682" alt="image" src="https://github.com/user-attachments/assets/72098bb8-8f19-41fc-8258-da217da0e2ef" />
*

---

## Installation

1. Clone the repository:

```bash
git clone [https://github.com/parvendrakumar/Phone-Number-Lookup.git]
cd phone-lookup
```

2. Configure API key:

* Open `api.php`
* Add your **NumVerify API key**:

```php
define('NUMVERIFY_API_KEY', 'YOUR_API_KEY_HERE');
```

> You can get a free API key from [NumVerify](https://numverify.com/).

3. Run on local server:

* Using XAMPP, WAMP, or any PHP server.
* Place the folder in your `htdocs` or server directory.
* Open `http://localhost/phone-lookup` in browser.

---

## Usage

1. Enter a phone number including country code (e.g., `+91 9876543210`)
2. Select the country from the dropdown
3. Click **Search**
4. Results will show:

* Phone number
* Country
* Carrier
* Location
* Line type
* Validity

5. Click **Search Another** to reset form

---

## Folder Structure

```
phone-lookup/
│
├── index.html       # Frontend
├── styles.css       # CSS styling
├── script.js        # Frontend JavaScript
├── api.php          # PHP backend for API requests
└── README.md        # Project documentation
```

---

## Dependencies

* PHP >= 7.4
* NumVerify API Key
* Modern browser with JavaScript enabled

---

## License

This project is **MIT licensed**.

---

## Contact

For questions or suggestions, contact **[Parvendrakumar]**.
