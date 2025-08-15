#include "UI.h"
#include <cstdlib>
#include <fstream>
#include <string>
using namespace std;

class Installer {
  ConsoleUI ui;
  string installer;
  string *steps;

  void GetTools() {
    do {
      ui.title();
      ui.fancyMenu(steps, 1, false, "Steps");
      ui.loading(steps[1]);

      if (installer == "apt")
        system("sudo apt-get update && sudo sudo "
               "apt-get "
               "install git apache2 nodejs npm mysql-server php php-mysqli -y");
      else if (installer == "pacman")
        system("sudo pacman -S --noconfirm git "
               "apache nodejs npm mysql php php-mysqli");
      else if (installer == "yum")
        system("sudo yum update -y && sudo yum install git httpd nodejs npm "
               "mysql-server php php-mysqli -y");
      else if (installer == "dnf")
        system("sudo dnf upgrade -y && sudo dnf install git httpd nodejs npm "
               "mysql-server php php-mysqli -y");
      system("clear");

      // if (!(system("git -v") || system("node -v") || system("npm -v") ||
      //       system("mysql -v") || system("php -v") ||
      //       system("php -m | grep -i mysqli")))
      //   if (system("apache2 -v") == 0 || system("apache -v") == 0 ||
      //       system("httpd -v") == 0)
      //     break;
      if (system("git -v") == 0 && system("node -v") == 0 &&
          system("npm -v") == 0 && system("mysql --version") == 0 &&
          system("php -v") == 0 && system("php -m | grep -i mysqli") == 0)
        if (system("apache2 -v") == 0 || system("apache -v") == 0 ||
            system("httpd -v") == 0)
          break;
      system("clear");
    } while (true);

    steps[1] = "✅  " + steps[1];
    GetFiles();
  }

  void GetFiles() {
    ui.title();
    ui.fancyMenu(steps, 2, false, "Steps");

    do {
      ui.loading(steps[2]);
    } while (
        system(
            "sudo git clone "
            "https://github.com/umair-gititall/Final-Project-DB.git "
            "/tmp/fpdb && sudo cp -r /tmp/fpdb/* /var/www/html/ && sudo rm -rf "
            "/tmp/fpdb") != 0);

    steps[2] = "✅  " + steps[2];
    SetDatabase();
  }

  void SetDatabase() {
    ui.title();
    ui.fancyMenu(steps, 3, false, "Steps");

    system("sudo systemctl enable mysql");
    system("sudo systemctl start mysql");

    do {
      ui.loading(steps[3]);
    } while (system("mysql -u root -p -e \"SOURCE "
                    "/var/www/html/Requirements/Database.sql; "
                    "CREATE USER 'LostFoundSystem'@'localhost' IDENTIFIED BY "
                    "'LostFoundManagementSystem'; GRANT ALL PRIVILEGES ON "
                    "LostFoundDB.* TO "
                    "'LostFoundSystem'@'localhost'; FLUSH PRIVILEGES;\"") != 0);

    steps[3] = "✅  " + steps[3];
    SetApiKey();
  }

  void SetApiKey() {
    string temp[] = {"Open https://aistudio.google.com/apikey",
                     "Press Create API Key", "Press Copy", "Paste Here"};
    string check = "";
    fstream validator;

    ui.title();
    ui.fancyMenu(steps, 4, false, "Steps");

    do {
      ui.fancyMenu(temp, 4, true, "Gemini API Key");
      getline(cin, installer);
      system(("curl -s -o /dev/null -w \"%{http_code}\n\" "
              "\"https://generativelanguage.googleapis.com/v1/models?key=" +
              installer + "\" > /tmp/check.txt")
                 .c_str());

      validator.open("/tmp/check.txt");
      validator >> check;

      if (check == "200")
        break;
      ui.BoxedMessage("Invalid Api Key");
    } while (true);

    system("rm /tmp/check.txt");
    validator.close();
    validator.open("/var/www/html/Requirements/Ai/.env", ios::out);
    validator << "GOOGLE_API_KEY=" << installer;

    validator.close();
  }

public:
  Installer()
      : steps(new string[6]{"Determining OS", "Getting Required Tools",
                            "Getting Required Files", "Setting Database",
                            "Setting Gemini Api Key"}) {
    do {
      ui.title();
      ui.fancyMenu(steps, 0, true, "Start Installation (y/n)");
      getline(cin, installer);

      if (installer == "y" || installer == "Y") {
        ui.loading(steps[0]);

        if (system("apt -v") == 0)
          installer = "apt";
        else if (system("pacman -v") == 0)
          installer = "pacman";
        else if (system("yum -v") == 0)
          installer = "yum";
        else if (system("dnf -v") == 0)
          installer = "dnf";
        else
          installer = "none";
        break;
      } else if (installer == "n" || installer == "N") {
        installer = "none";
        break;
      }

      system("clear");
    } while (true);

    steps[0] = "✅  " + steps[0];

    if (installer != "none")
      GetTools();
    else
      ui.BoxedMessage("OS not Supported!!");
  }
};

int main() {
  Installer Install;

  return 0;
}