func basename(s string) string {
  slash := strings.LastIndex(s, "/")
  s = s[slash + 1:]
  if dot := strings.LastIndex(s, "."); dot >= 0 {
    s = s[:dot]
  }
  return s
}

func reverse(s []string) {
	for i, j := 0, len(s) - 1; i < j; i, j = i + 1, j - 1 {
		s[i], s[j] = s[j], s[i]
	}
}
