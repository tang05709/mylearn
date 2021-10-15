// 获取数字前ct位
unsigned long left(unsigned long num, unsigned ct)
{
	unsigned digits = 1;
	unsigned long n = num;

	if (ct == 0 || num == 0) return 0;

	while (n /= 10) {
		digits++;
	}
	if (digits > ct) {
		ct = digits - ct;
		while (ct--) {
			num /= 10;
		}
		return num;
	}
	else {
		return num;
	}
}
// 截取字符串前n位
char* left(const char* str, int n)
{
	if (n < 0) n = 0;
	char* p = new char[n + 1];
	int i;
	for (i = 0; i < n && str[i]; i++) {
		p[i] = str[i];
	}
	while (i <= n) {
		p[i++] = '\0';
	}
	// 使用后记得delete[] p; char* temp = left("test", 2); delete[] temp;
	return p;
}
