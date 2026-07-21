# LOOO Electronics & Computers Online Store System

UECS2094/UECS2194 Web Application Development

**PHP Authentication Integration**
- Login system with database storage
- User registration with validation
- Session management
- Dynamic header based on login status
- Welcome message for logged-in users
- Proper logout functionality

## **Setup Instructions:**

### **Option 1:WAMP**
1. **Download WAMP** from [https://www.wampserver.com/](https://www.wampserver.com/)
2. **Install WAMP** (choose default options)
3. **Copy your files** to `C:\wamp64\www\Assignment\Main page\`
4. **Start WAMP** (green icon in system tray)
5. **Open browser** and go to: `http://localhost/Assignment/Main%20page/Login.php`

### **Option 2: PHP Built-in Server (Quick Test)**
1. **Install PHP** from [https://windows.php.net/download/](https://windows.php.net/download/)
2. **Add PHP to PATH** environment variable
3. **Open command prompt** in your project folder
4. **Run:** `php -S localhost:8000`
5. **Open browser** and go to: `http://localhost:8000/Login.php`

## **File Structure:**
```
Main page/
├── Login.php              # User login page
├── Create Account.php     # User registration page
├── main page.php          # Main landing page (with auth integration)
├── config.php             # Database configuration
├── logout.php             # Logout handler
├── dashboard.php          # Optional dashboard (not used in main flow)
├── main page.html         # Original HTML version
├── CreateAccount.js       # Original JavaScript
├── profile.html           # User profile page
├── rewards.html           # Rewards page
├── Technical page.html    # Technical support page
└── Various image files    # Product and brand images
```

## **Authentication Works:**

### **1. User Registration:**
- User fills out `Create Account.php`
- Data validated and stored in MySQL database
- Password securely hashed
- Redirects to main page after success

### **2. User Login:**
- User enters credentials in `Login.php`
- Credentials verified against database
- Session created on success
- Redirects to main page

### **3. Main Page Integration:**
- **When NOT logged in:** Shows "Login / Sign in" button
- **When logged in:** Shows user profile with name, email, and logout button
- **Welcome message** appears below header for logged-in users
- **All original functionality** preserved (carousel, products, cart, etc.)

### **4. Session Management:**
- User stays logged in across page refreshes
- Secure logout removes session data
- Database connection handled automatically

## **Database Setup:**

The system automatically creates:
- **Database:** `user_auth`
- **Table:** `users` with fields:
  - `id` (auto-increment)
  - `first_name`
  - `last_name`
  - `email` (unique)
  - `password` (hashed)
  - `created_at`

## **Testing the System:**

### **Step 1: Create Account**
1. Open `Create Account.php`
2. Fill in your details
3. Submit the form
4. Should redirect to main page

### **Step 2: Test Login**
1. Open `Login.php`
2. Enter your credentials
3. Submit the form
4. Should redirect to main page with personalized header

### **Step 3: Test Main Page**
1. Main page should show your name and email
2. Welcome message should appear
3. Logout button should be visible
4. All original functionality should work

### **Step 4: Test Logout**
1. Click logout button
2. Should redirect to login page
3. Session should be cleared

## **Common Issues & Solutions:**

### **"Connection failed" Error:**
- Make sure MySQL service is running
- Check if XAMPP/WAMP is started
- Verify database credentials in `config.php`

### **"Unknown database" Error:**
- The system automatically creates the database
- Make sure MySQL service is running
- Check file permissions

### **Page not loading:**
- Make sure Apache service is running
- Check file paths are correct
- Verify PHP is installed and configured

### **Login not working:**
- Check if database table was created
- Verify user registration was successful
- Check browser console for JavaScript errors

## **Troubleshooting:**

### **Check Services:**
- **XAMPP:** Open Control Panel → Check Apache and MySQL status
- **WAMP:** Look for green icon in system tray
- **Built-in server:** Check command prompt for errors

### **Check Database:**
- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Look for `user_auth` database
- Check `users` table exists

### **Check File Permissions:**
- Make sure all `.php` files are readable
- Check if web server can access the directory

### **Check Browser Console:**
- Press F12 → Console tab
- Look for JavaScript errors
- Check Network tab for failed requests

## **What You Can Do Now:**

1. **Test the complete flow:** Register → Login → Use main page → Logout
2. **Customize the system:** Add more user fields, change styling, etc.
3. **Add features:** Password reset, email verification, user roles
4. **Deploy online:** Upload to web hosting with PHP/MySQL support

## **Need Help?**

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all services are running
3. Check file paths and permissions
4. Look for error messages in browser console

## **License**
This project for education purpose only

---

** Your authentication system is now fully functional and ready to use!**
