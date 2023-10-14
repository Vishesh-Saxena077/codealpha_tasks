# **Task 1 : Login Authentication**

🔒  **Lockout Time Authentication** : I've implemented a security feature to protect user accounts.Indicating enhanced security.

👾  **Failed Login Attempts** : The system tracks failed login attempts, which signifies that unauthorized access is being monitored.

⏳  **Lockout Mechanism** : When the threshold of failed attempts (e.g., 5) is reached, the system initiates a lockout.

🔥  **Preventing Repeated Attempts** : During the lockout period, the system prevents repeated login attempts, signifying that unauthorized access is temporarily blocked.

🔄  **Automatic Reset** : After a designated time period (e.g., 24 hours), the system automatically resets the login attempts and lockout time, allowing the user to try again.

🛡️  **Enhanced Security** : This mechanism not only protects against brute-force attacks but also enhances account security.Strengthened security.

📊  **Database Integration** : We utilize a database to store user data securely. This includes information about login attempts, lockout times, and user account details.

📣  **User-Friendly Feedback** : Clear feedback is provided to users, ensuring they are informed about the lockout period or successful logins. Signifies communication with users.

💼  **Database Integrity** : The integrity of user data and account information is maintained, representing organization and data management.

🔧  **Database Configuration** : We carefully monitor and maintain the database's configuration (🔧) to ensure the correct functioning of the lockout time authentication.

🔒🔑 **Admin Privileges:** For added security, only admin users have the privilege to reset lockout times. This ensures that authorized personnel can manage user accounts effectively.

⏳ **Lockout Time Effect:** Once a lockout time is entered in the database, even if a user enters the correct password, they won't be able to log in before the designated 24-hour lockout period expires. This additional layer of security enhances protection against unauthorized access.
