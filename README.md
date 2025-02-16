# Team Members:
## Ahmed Hassan Mahmoud  - 202003058 (useername: Nekura058)
## Mufeed Mamadan Elya   - 202003014 (useername: mufeed-kodi)
## Ahmed Mahmoud Hamza   - 202003039 (useername: ahmedmoh100)
## Mohamed Jamal Ali     - 202020002 (useername: Hilmy91)
## Montaser Mohamed Adam - 202003022 (useername: kafaniTDM)

# Isekai Store Online  

##  Problem Statement  
Many online stores lack efficient product browsing, smooth checkout processes, and proper admin management. Customers often struggle with:  
- Finding products easily due to poor categorization.  
- Managing their cart with real-time updates.  
- Tracking orders and deliveries after purchase.  
- Slow response times from store admins.  

On the admin side, traditional e-commerce platforms lack:  
- Efficient product and order management.  
- Delivery agent assignment & tracking.  
- A secure and intuitive dashboard for operations.  

##  Solution  
Isekai Store Online solves these problems by providing:  
 User-friendly product browsing with categorized listings.  
 Seamless cart & checkout functionality.  
 Order tracking & delivery status updates.  
 Admin dashboard for easy management of products, orders, and deliveries.  
 Delivery agent assignment system for tracking shipments.  
 Secure login system for both users and admins.  

## Features  
###  Customer Side  
- Browse products by category  
- Add/remove items from the cart  
- Checkout process  
- User authentication (Register/Login/Logout)    

### Admin Side  
- Secure admin login  
- Manage products (add, edit, delete)  
- Manage orders and update delivery status  
- Assign and manage delivery agents (via calls)
- View sales analytics (future update)
## Important Note: To access the admin side you must access it through this URL "http://isekai-store/isekai_store-main/admin/admin_login.php"
## Admin Account:  username: "fido" - password: "alyosha"
## To add a new admin you must open the register_admin.php in a text editor and edit the username and password then execute the code. ##

##  Technologies Used  
- Frontend: HTML, CSS, JavaScript  
- Backend: PHP  
- Database: MySQL  
- Server: WAMP  

##  Setup Instructions  
1. Clone the repository:  
   ```bash
   git clone https://github.com/mufeed-kodi/isekai_store.git
   ```
2. Set up WAMP.  
3. Import the database:  
   - Open phpMyAdmin
   - Create a database (e.g., `isekai_store`)  
   - Import `isekai_db2.sql`  
4. Configure database connection in `config.php`. 
5. Run the project on `localhost/isekai_store-main/`. 

## Future Enhancements    
Custom order requests (allow users to request specific items).  
Online payment integration with multiple gateways.  
Having delivery agents assignment.
Admin analytics dashboard for insights on sales, customers, and trends.  
Customer reviews & ratings to improve product trust.  

## Contact  
For contributions or inquiries, feel free to reach out!  
