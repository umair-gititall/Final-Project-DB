#include <iostream>
#include <sys/ioctl.h>
#include <thread>
#include <unistd.h>
using namespace std;

class ConsoleUI {
  const string WHITE = "\033[97m";
  const string RED = "\033[31m";
  const string GREEN = "\033[32m";
  const string YELLOW = "\033[33m";
  const string CYAN = "\033[36m";
  const string MAGENTA = "\033[95m";
  const string GREY = "\033[90m";
  const string ITALIC = "\033[3m";
  const string RESET = "\033[0m";
  const string TOP = "\033[1;1H";
  const string SAVE = "\033[s";
  const string JUMP = "\033[2;1H";

  int width() {
    struct winsize w;
    ioctl(STDOUT_FILENO, TIOCGWINSZ, &w);
    return w.ws_col;
  }

  // Loading Bar
  void progressBar(int total = 20, int delayMS = 100) {
    cout << YELLOW << "Progress: [" << GREEN;
    for (int i = 0; i < 20; i++) {
      cout << "#" << flush;
      this_thread::sleep_for(chrono::milliseconds(delayMS));
    }
    cout << YELLOW << "] Done!" << RESET;
    this_thread::sleep_for(chrono::milliseconds(delayMS + 50));
  }

  // Text Animations
  void typingEffect(string message, int delayMS = 40) {
    for (char c : message) {
      cout << c << flush;
      this_thread::sleep_for(chrono::milliseconds(delayMS));
    }
  }

public:
  // Headings
  void title(string message = "Lost And Found Management System",
             bool AllowColors = true) {
    string spaces((width() - message.length()) / 2, ' ');
    string line(message.length() + 10, '=');
    int color = rand() % 5;

    cout << spaces << CYAN << ITALIC << line << endl;

    if (AllowColors) {
      if (color == 0)
        cout << GREY;
      else if (color == 1)
        cout << RED;
      else if (color == 2)
        cout << MAGENTA;
      else if (color == 3)
        cout << WHITE;
      else if (color == 4)
        cout << YELLOW;
    }

    cout << spaces << "     " << message << endl;
    cout << spaces << CYAN << line << endl << RESET;
  }

  // Pop Ups
  void BoxedMessage(string message, bool effect = false) {
    string spaces((width() - message.length()) / 2, ' ');
    string line(message.length() + 4, '=');

    cout << spaces << GREEN << "  +" << line << "+\n" << spaces << "  |  ";
    if (effect)
      typingEffect(message);
    else
      cout << message;

    cout << "  |\n" << spaces << "  +" << line << "+\n" << RESET;
  }

  // Printing Menu
  void fancyMenu(string *options, int size, bool input,
                 string heading = "Main Menu") {
    string spaces((width() - heading.length() - 15) / 2, ' ');

    title(heading, false);
    for (int i = 0; i < size; i++) {
      cout << spaces << YELLOW << " [" << i + 1 << "] " << RESET;
      typingEffect(options[i]);
      cout << endl;
    }
    if (input)
      cout << CYAN << " Root@Console ➤ " << RESET;
  }

  void loading(string message) {
    system("clear");
    title();
    BoxedMessage(message);
    string spaces((width() - 38) / 2, ' ');
    cout << spaces;
    progressBar();
    cout << endl;
    system("clear");
  }

  void pause() {
    cout << "Enter to continue.....";
    cin.get();
  }
};